<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Cat;
use App\Http\Requests\Admin\UpdateBlogRequest;
// use Illuminate\Support\Facades\Auth;

class AdminBlogController extends Controller
{
    //ブログ一覧表示
    public function index()
    {
        $blogs = Blog::latest('updated_at')->simplepaginate(10);
        $user = \Illuminate\Support\Facades\Auth::user();
        return view('admin.blogs.index', ['blogs' => $blogs]);
    }

    //ブログ投稿表示
    public function create()
    {
        return view('admin.blogs.create');
    }

    //ブログ投稿処理
    public function store(StoreBlogRequest $request)
    {
        $saveImagePath = $request->file('image')->store('blogs', 'public');
        $blog = new Blog($request->validated());
        $blog->image = $saveImagePath;
        $blog->save();

        return to_route('admin.blogs.index')->with('success', 'ブログを投稿しました');
    }

    public function show($id)
    {
        //
    }

    //指定したIDのブログの編集画面
    public function edit(Blog $blog)
    {
        // $blog = Blog::findOrFail($id);
        $categories = Category::all();
        $cats = Cat::all();
        $user = \Illuminate\Support\Facades\Auth::user();
        return view('admin.blogs.edit', ['blog' => $blog, 'categories' => $categories, 'cats' =>$cats,]);
    }

    //指定したIDのブログを更新
    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $updateData = $request->validated();

        //画像を変更する場合
        if($request->has('image'))
        {
            //変更前の画像削除
            Storage::disk('public')->delete($blog->image);
            //変更後の画像をアップロード、保存パスを更新対象データにセット
            $updateData['image'] = $request->file('image')->store('blogs', 'public');
        }
        $blog->category()->associate($updateData['category_id']);
        $blog->cats()->sync($updateData['cats']);
        $blog->update($updateData);

        return to_route('admin.blogs.index')->with('success', 'ブログを更新しました');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        Storage::disk('public')->delete($blog->image);

        return to_route('admin.blogs.index')->with('success', 'ブログを削除しました');
    }
}
