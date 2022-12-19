<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Requests\UsersMultiUpdateRequest;

use App\UseCases\UsersMultiUpadateUseCase;

class UsersController extends Controller
{
    public function multiupdate(UsersMultiUpdateRequest $request, UsersMultiUpadateUseCase $useCase)
    {
        $useCase($request->users);
        return to_route('users.index')->with([
            'message' => '登録しました。',
            'status' => 'success'
        ]);
    }

    public function index()
    {
        return Inertia::render('Users/Index', [
            'users' => User::ordered()->get(),
        ]);
    }


}
