<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string', 'max:5000'],
        ]);

        Mail::to('info@marscoin.org')->send(new ContactFormMail($validated));

        return back()->with('message_sent', 'Your message has been sent successfully!');
    }
}
