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
}
