<?php

namespace App\Views;
class UserView
{
    public function rewriteUser($data)
    {
        $user = \App\Models\User::find($data['userId'])->update(['name' => $data['name'], 'email' => $data['email'], 'password' => $data['password']]);
    }
}
