<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    public function sendEmail(Request $request)
    {
        $details = $request->only(['name', 'email', 'subject', 'text']);

        Mail::to('info@marscoin.org')->send(new ContactFormMail($details));

        return back()->with('message_sent', 'Your message has been sent successfully!');
    }
}
