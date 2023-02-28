<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use SendGrid;
//use SendGrid\Mail\Mail;
//use \Symfony\Component\HttpFoundation\Response;

class MailingController extends Controller
{
    public function index()
    {
        return view('mailing', ['users' => User::all()]);
    }

    public function sendMail(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|email',
            'user' => 'required|email',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);
        $from = new \SendGrid\Mail\From($validated['from']);
        $tos = new \SendGrid\Mail\To($validated['user']);
        /* Sent subject of mail */
        $subject = new \SendGrid\Mail\Subject($validated['subject']);
        /* Set mail body */
        $htmlContent = new \SendGrid\Mail\HtmlContent(nl2br($validated['body']));
        $email = new \SendGrid\Mail\Mail(
            $from,
            $tos,
            $subject,
            null,
            $htmlContent
        );
        /* Create instance of Sendgrid SDK */
        $sendgrid = new SendGrid(getenv('MAIL_PASSWORD'));
        /* Send mail using sendgrid instance */
        $response = $sendgrid->send($email);
        if ($response->statusCode() == 202) {
            return back()->with(['success' => "メールを送信しました"]);
        }
        return back()->withErrors(json_decode($response->body())->errors);
    }

    public function XsendMail(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|email',
            'users' => 'required|array',
            'users.*' => 'required',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);
        $from = new \SendGrid\Mail\From($validated['from']);
        /* Add selected users email to $tos array */
        $tos = [];
        foreach ($validated['users'] as $user) {
            array_push($tos, new \SendGrid\Mail\To(json_decode($user)->email, json_decode($user)->name));
        }
        /* Sent subject of mail */
        $subject = new \SendGrid\Mail\Subject($validated['subject']);
        /* Set mail body */
        $htmlContent = new \SendGrid\Mail\HtmlContent(nl2br($validated['body']));
        $email = new \SendGrid\Mail\Mail(
            $from,
            $tos,
            $subject,
            null,
            $htmlContent
        );
        /* Create instance of Sendgrid SDK */
        $sendgrid = new SendGrid(getenv('MAIL_PASSWORD'));
        /* Send mail using sendgrid instance */
        $response = $sendgrid->send($email);
        if ($response->statusCode() == 202) {
            return back()->with(['success' => "メールを送信しました"]);
        }
        return back()->withErrors(json_decode($response->body())->errors);
    }
}