<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Email;
use SendGrid;
use SendGrid\Mail\Mail;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        // Validate the request
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // Create a new email entry
        $email = new Email();
        $email->to = $request->to;
        $email->subject = $request->subject;
        $email->body = $request->body;
        $email->save();

        // Send email using SendGrid
        $sendgridMail = new Mail();
        $sendgridMail->setFrom("your_email@example.com", "Your Name");
        $sendgridMail->setSubject($email->subject);
        $sendgridMail->addTo($email->to);
        $sendgridMail->addContent("text/plain", $email->body);

        $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));

        try {
            $response = $sendgrid->send($sendgridMail);
            return response()->json(['message' => 'Email sent successfully!'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to send email.'], 500);
        }
    }
}
