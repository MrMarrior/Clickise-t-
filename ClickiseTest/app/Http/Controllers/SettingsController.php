<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Views\UserView;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function showSettings(): View
    {
        return view('settings');
    }

    public function getUserData()
    {
        // Если была система регистрации, можно было взять модель конкретного пользователя под аккаунтом
        $user = User::first();
        return $user;
    }

    protected function changeSettings(Request $request, UserView $userView)
    {
        $userView->rewriteUser($request);
        return 0;
    }
}
