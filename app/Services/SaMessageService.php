<?php

namespace App\Services;


use App\Models\SaMessage;
use App\Models\SaMessageNUser;
use Illuminate\Support\Facades\DB;

class SaMessageService extends BaseService
{

public static function notify($receiverIds,$subject, $content) {

	if (!is_array($receiverIds) || empty($receiverIds)) {
	        throw new Exception("reiceiverIds must be an array and not empty");
	    }
	    $senderId = config("notification.senderId");
	    $senderUser = null;
	    $users = DB::table('chi_user')->select(['id','full_name'])->get();
	    $receivers = [];
	    foreach ($users as $user) {
	        if ($user->id == $senderId) {
	            $senderUser = $user;
	        }
	        foreach ($receiverIds as $receiverId) {
	            if ($user->id == $receiverId) {
	                $receivers[] = $user;
	                break;
	            }
	        }
	    }
	    if (empty($senderUser)) {
	        $senderUser = $users = DB::table('chi_user')->select(['id','full_name'])->where('id','=',5303) ->first();
	    }
	    $input = [
	        "subject" => $subject,
	        "content" => $content,
	        "receivers" => json_encode($receivers),
	        "is_deleted_by_sender" => 0,
	        "create_time" => new \DateTime(),
	        "sender_id" => $senderUser->id,
	        "sender_name" => $senderUser->full_name,
	    ];
	    DB::beginTransaction();
	    try {
	        $message = SaMessage::create($input);
	        foreach ($receiverIds as $receiverId) {
	            $messageNUser = [
	                "user_id" => $receiverId,
	                "message_id" => $message->id,
	                "is_read" => 0,
	                "is_deleted" => 0
	            ];
	            SaMessageNUser::create($messageNUser);
	        }
	        DB::commit();
	    } catch (Exception $ex) {
	        DB::rollBack();
	        throw $ex;
	    }
	}
}