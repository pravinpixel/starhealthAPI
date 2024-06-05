<?php
namespace App\Helpers;
use App\Models\ActivityLog;
class LogHelper
{
	 public static function AddLog($user,$user_id,$action,$information,$message)
    {
    	 try {
                $log = new ActivityLog();
                $log->user = $user;
                $log->user_id = $user_id;
                $log->action = $action;
                $log->information = $information;
                $log->message = $message;
                $log->save();
           
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
        return $log;
    }
}