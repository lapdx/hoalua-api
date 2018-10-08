<?php
namespace App\Services;


use App\Models\HrmStaff;
use Illuminate\Support\Facades\DB;

class NotificationService extends BaseService
{
	public function sendMessageToStaff($staffId, $subject, $content, $messageType = ["push", "email", "inbox"]) {
        $user = \App\Services\StaffService::getUserByStaffId($staffId, HrmStaff::STATUS_WORKING);
        $this->sendMessageToUser([$user->id], $subject, $content, $messageType);
    }

    public function sendMessageToAllStaffs($subject, $content, $messageType = "inbox") {
        $users =  \App\Services\StaffService::listAllUsers(HrmStaff::STATUS_WORKING);
        $userIds = [];
        foreach ($users as $user) {
            $userIds[] = $user->id;
        }
        $this->sendMessageToUser($userIds, $subject, $content, $messageType);
    }

    public function sendMessageToDepartment($departmentId, $subject, $content, $messageType = ["push", "email", "inbox"]) {
        $users =  \App\Services\StaffService::listUsersByDepartmentId($departmentId, HrmStaff::STATUS_WORKING);
        $userIds = [];
        foreach ($users as $user) {
            $userIds[] = $user->id;
        }
        $this->sendMessageToUser($userIds, $subject, $content, $messageType);
    }

    public function sendMessageToGroup($groupId, $subject, $content, $messageType = ["push", "email", "inbox"]) {
        $users = DB::table('sa_user_n_role')->where('group_id','=',$groupId)->get();
        $userIds = [];
        foreach ($users as $user) {
            $userIds[] = $user->reference_id;
        }
        $this->sendMessageToUser($userIds, $subject, $content, $messageType);
    }

    private function sendMessageToUser($userIds, $subject, $content, $messageType = ["push", "email", "inbox"]) {

        if(count($userIds)>0){
            if(in_array("push",$messageType)){
                $devices = DB::table('chi_device')->whereIn('user_id', $userIds)->pluck('device_token')->all();
                $target = array_values($devices);

                $data = array (
                        "type" => $messageType ? $messageType : null
                );
                $notification = array (
                        "body" => $content ? strip_tags($content) : '',
                        "title" => $subject ? $subject : "Megaads",
                        "icon" => "myicon",
                        "sound"=> "default",
                        "click_action"=>"FCM_PLUGIN_ACTIVITY",
                        "badge" => 0    
                );
                \App\Services\PushNotificationService::sendMessage($data, $notification, $target);
            }
            if(in_array("inbox",$messageType)){
                \App\Services\SaMessageService::notify($userIds, $subject, $content);
            }
            if(in_array("email",$messageType)){
                $emails = DB::table('chi_user')->whereIn('id', $userIds)->pluck('email')->all();
                $emailsTo = array_values($emails);
                $data = [];
                $data['content'] = $content;
                \App\Services\SendEmailService::sendEmail($emailsTo, $data, $subject, "email.notification");
            }

        }
    }

}