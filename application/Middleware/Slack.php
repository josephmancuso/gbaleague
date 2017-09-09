<?php

namespace Middleware;

// composer require maknz/slack

use App\League\Models\Leagues;

class Slack
{
    public function __construct(\App\League\Models\Leagues $league)
    {
        $this->webhook = $league->slackwebhook;
        $this->channel = $league->slackchannel ?: '#general';
    }

    public function client() {
        $settings = [
            'username' => 'GBALeague.com',
            'channel' => $this->channel,
            'link_names' => true
        ];
        return new \Maknz\Slack\Client($this->webhook, $settings);
    }

    public static function acceptIntegration()
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', 'https://slack.com/api/oauth.access', [
            'form_params' => [
                'client_id' => getenv('slack_client_id'),
                'client_secret' => getenv('slack_client_secret'),
                'code' => $_GET['code']
            ]
        ]);

        return json_decode($res->getBody());
    }
}
