<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\User;
use App\Services\SendCodeService;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class ConfirmationActionController
{

    protected $sendCodeService;

    public function __construct(SendCodeService $sendCodeService)
    {
        $this->SendCodeService = $sendCodeService;
    }
    public function sendCode(Request $request){
        $user = User::find($request['userId']);

        switch ($request['confirmation']) {
            case 'Telegram':
                //вызов service имитирующий отправку кода на telegram пользователя
                $response['code'] = $this->SendCodeService->sendCodeTelegram($user);
                break;
            case 'SMS':
                // вызов service имитирующий отправку кода через SMS
                $response['code'] = $this->SendCodeService->sendCodeSMS($user);
                break;
            case 'Email':
                // вызов service имитирующий отправку кода на Email
                $response['code'] = $this->SendCodeService->sendCodeEmail($user);
                break;
        }

        return $response;
    }
}
