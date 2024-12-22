<?php

namespace App\Services;

use App\Models\CampaignDetail;
use App\Utility\RedisUtility;
use Illuminate\Support\Facades\Mail;

class SendEmailService
{

    public function send($Campaign)
    {
        $time_start = microtime(true);

        do {
            $get_detail = null;
            try {
                $details = RedisUtility::queuePop(['Redis_Send_Email_Campaign_' . $Campaign->id]);
                if ($details == null) {
                    break;
                }
                $_details = json_decode($details);
                $get_detail = $_details;
                Mail::send($_details->view, ['order' => $_details->order], function ($message) use ($_details) {
                    $message->to($_details->email)
                        ->subject($_details->subject)
                        ->from($_details->from, 'no-reply');
                });

                if ($_details != null) {
                    $Campaign->email_sended = $Campaign->email_sended + 1;
                    $Campaign->save();
                }

                $time_end = microtime(true);
                $time = $time_end - $time_start;
                CampaignDetail::create([
                    'Campaign_id' => $Campaign->id,
                    'type' => $Campaign->type,
                    'type_Campaign' => 0,
                    'view' => 'view',
                    'status' => 0,//thành công
                    'contact' => $_details->email,
                    'reason' =>$_details->subject,
                    'content'=> json_encode($get_detail)
                ]);
            } catch (\Exception $e) {
                CampaignDetail::create([
                    'Campaign_id' => $Campaign->id,
                    'type' => $Campaign->type,
                    'type_Campaign' => 0,
                    'view' => 'view',
                    'status' => 2, //thất bại
                    'contact' => $_details->email,
                    'reason' =>$_details->subject,
                    'content'=> json_encode($get_detail)
                ]);
            }
        } while ($_details != null || $time < 5);

        $total = json_decode($Campaign->total);
        if ($Campaign->email_sended >= $total->email) {
            $Campaign->update(['run' => 1]);
        }
    }

}
