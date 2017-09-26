<?php

namespace Middleware;

use App\League\Models\Leagues;

class Discord
{
    public function __construct(\App\League\Models\Leagues $league)
    {
        $this->id = $league->discordid;
        $this->token = $league->discordtoken;
    }

    public function send($message)
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', "https://discordapp.com/api/webhooks/$this->id/$this->token", [
            'form_params' => [
                'content' => "$message",
                'username' => 'GBALeague.com'
            ]
        ]);
    }

    public function client() {
        $settings = [
            'username' => 'GBALeague.com',
            'channel' => $this->channel,
            'link_names' => true
        ];
      //   return new \Maknz\Slack\Client($this->webhook, $settings);
    }

    public static function acceptIntegration()
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', 'https://discordapp.com/api/oauth2/token', [
            'form_params' => [
                'client_id' => getenv('discord_client_id'),
                'client_secret' => getenv('discord_client_secret'),
                'code' => $_GET['code'],
                'grant_type' => 'authorization_code'
            ]
        ]);

        return json_decode($res->getBody());
    }
}
