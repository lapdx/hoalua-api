<?php
/**
 * Created by PhpStorm.
 * User: bachnguyen
 * Date: 6/30/18
 * Time: 9:10 AM
 */

namespace App\Services;


use App\Models\ChiUser;
use Illuminate\Support\Facades\DB;

class UserService extends BaseService
{
    const MODEL = ChiUser::class;
    //
    public function query($filter = [])
    {
        $query = parent::query($filter);
        if (array_key_exists('name', $filter)) {
            $query->where('name', 'LIKE','%'.$filter['name'].'%');
        }
        if (array_key_exists('email', $filter)) {
            $query->where('email', 'LIKE','%'.$filter['email'].'%');
        }
        if (array_key_exists('status', $filter)) {
            $query->where('status', '=', $filter['status']);
        }
        if (array_key_exists('searchUser', $filter)) {
            $str = preg_replace('/s+/', ' ', $filter['searchUser']);
            $arr = explode(" ",$str);
            $search = implode(" +",$arr);
            $search = '+' . $search . '*';
            $query->whereRaw("MATCH (name, phone, email) AGAINST ('" . $search . "' IN BOOLEAN MODE)");
        }
        $query->orderBy('id', 'DESC');
        return $query;
    }

    public function forgotPassword($emailTo) {
        if(!ChiUser::where('email', '=', $emailTo)->exists()) {
            return 0;
        }
        if(DB::table('resets')->where('status', '=', 'enable')->exists()) {
            $tokenData = DB::table('resets')->where('status', '=', 'enable')->where('email', '=', $emailTo)->first();
            $data = json_decode(json_encode($tokenData), TRUE);
        }else{
            $data = array(
                'token' => $this->generateRandomNumber(6),
                'status' => 'enable',
                'email' => $emailTo,
                'created_at' => date('Y-m-d H:i:s')
            );
            DB::table('resets')->insert($data);
        }

        $template = view('email.forget-password', ['data' => $data]);
        $transport = (new \Swift_SmtpTransport(env('MAIL_HOST'), env('MAIL_PORT'), 'tls'))
            ->setUsername(env('MAIL_USERNAME'))
            ->setPassword(env('MAIL_PASSWORD'));
        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        // Create a message
        $message = (new \Swift_Message('Forgot Password ?'))
            ->setFrom([env('MAIL_FROM_ADDRESS') => env('MAIL_FROM_NAME')])
            ->setTo([$emailTo])
            ->setBody($template, 'text/html');

        // Send the message
        return $mailer->send($message);
    }

    public function verifyTokenForgotPassword($token, $email, $newPassword) {
        $tokenData = DB::table('resets')->where('token', '=', $token)->first();
        if(!empty($tokenData) && $tokenData->status == "enable" && $tokenData->email == $email) {

            ChiUser::where('email', '=', $tokenData->email)->update([
                'password' => $newPassword
            ]);

            return DB::table('resets')->where('token', '=', $token)->update([
                'status' => 'disable',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        return 0;
    }
}