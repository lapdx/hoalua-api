<?php
namespace App\Services;


use Illuminate\Support\Facades\DB;

class SendEmailService extends BaseService
{

public static function sendEmail($emailTo, $data, $subject, $type) {
        $template = "test";

        if($type == "email.notification"){
            $template = view('email.notification', $data);
        }
        $transport = (new \Swift_SmtpTransport(env('MAIL_HOST'), env('MAIL_PORT'), 'tls'))
            ->setUsername(env('MAIL_USERNAME'))
            ->setPassword(env('MAIL_PASSWORD'));
        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);
        // Create a message
        $message = (new \Swift_Message($subject))
            ->setFrom([env('MAIL_FROM_ADDRESS') => env('MAIL_FROM_NAME')])
            ->setTo($emailTo)
            ->setBody($template, 'text/html');

        // Send the message
        return $mailer->send($message);
    }
}