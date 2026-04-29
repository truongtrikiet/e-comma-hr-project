<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.mail.index');
    }

    public function sendMail(Request $request)
    {
        Mail::to($request->email)
            ->send(new SendMail($request->only(['email', 'content'])));
            $request->session()->flash(NOTIFICATION_SUCCESS, 'Send mail was successfully!');
        
        return view('mails.send_mail');
    }
}
