<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Mail;

class CommonController extends Controller
{
    public function index(){
        return Inertia::render('ContactUs');
    }

    public function sendMail(Request $request)
    {
//        dd($request->all());
      $validated =   $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255',
            'message' => 'required',
        ]);

        if ($validated) {
            $firstName = $request->first_name;
            $lastName = $request->last_name;
            $email = $request->email;
            $phoneNumber = $request->phone_number;
            $message = $request->message;
            $privacyAgreement = $request->policy_agreement;

            $content = [
                'fullName' => $firstName . ' ' . $lastName,
                'email' => $email,
                'phoneNumber' => $phoneNumber,
                'message' => $message,
                'privacyAgreement' => $privacyAgreement,
            ];

            \Mail::to(env('TO_EMAIL'))->send(new SendMail($content));

            return Inertia::location(route('mail.success'));
        } else {
            return redirect()->back();
        }
    }

    public function mailSuccess(){
        return Inertia::render('MessageSuccess');
    }
}
