<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        //
    }

    //ユーザー登録表示画面
    public function create()
    {
        return view('admin.users.create');
    }

    //ユーザー登録処理
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['image'] = $request->file('image')->store('users', 'public');
        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return back()->with('success', 'ユーザーを登録しました');
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }
}
