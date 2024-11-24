<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    public function send($data, $email)
    {
        Mail::raw($data['message'], function ($message) use ($email) {
            $message->to($email)
                ->subject('MDC Property Management System Password Reset')
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });

        return redirect()->back();
    }

}
