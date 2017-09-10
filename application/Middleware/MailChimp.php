<?php

namespace Middleware;

class MailChimp
{
    public static function subscribe(\App\League\Models\Accounts $account)
    {

        $MailChimp = new \DrewM\MailChimp\MailChimp(getenv('mailchimp_key'));

        $list_id = getenv('general_list');

        $result = $MailChimp->post("lists/$list_id/members", [
            'email_address' => $account->email,
            'status'        => 'subscribed',
        ]);

        return $result;
    }
    
    public static function premium(\App\League\Models\Accounts $account, $status = 'subscribed')
    {

        $MailChimp = new \DrewM\MailChimp\MailChimp(getenv('mailchimp_key'));

        $list_id = getenv('premium_list');

        $result = $MailChimp->post("lists/$list_id/members", [
            'email_address' => $account->email,
            'status'        => $status,
        ]);

        if ($account->ref) {
            $subscriber_hash = $MailChimp->subscriberHash($account->email);
            $result = $MailChimp->patch("lists/$list_id/members/$subscriber_hash", [
				'merge_fields' => ['CODE'=> $account->ref],
			]); 
        }

        return $result;
    }


}
