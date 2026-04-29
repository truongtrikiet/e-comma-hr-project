<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Acl\Acl;

class MailController extends Controller
{
    public function __construct(
        //
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_MAIL_MANAGE)->only('index');
    }

    public function index(Request $request)
    {
        return view('hr.mail.index');
    }

    public function sendMail(Request $request)
    {
        Mail::to($request->email)
            ->send(new SendMail($request->only(['email', 'content'])));
            $request->session()->flash(NOTIFICATION_SUCCESS, 'Send mail was successfully!');
        
        return view('mails.send_mail');
    }
}
