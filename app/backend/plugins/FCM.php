<?php

namespace Backend\Plugins;

use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Topic;
use paragraph1\phpFCM\Notification;

class FCM
{
    const API_KEY =
        'AAAAE-UH2kc:APA91bHc6Xnrx3D7N1nbQtgP7SzFJbJs4RIaBgucAEibCRIxCfDUFxn_Lfx6gdEz90sLqvHdkX5kaPte0' .
        'VzovrRlvIGEI3v4KgcPGs_1pGcwZwh9g3fi_5I_X45rEUP4UDisfgIO0xsd';

    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApiKey(self::API_KEY);
        $this->client->injectHttpClient(new \GuzzleHttp\Client());
    }

    public function send($title, $body, $topic)
    {
        $message = new Message();
        $message->addRecipient(new Topic($topic));
        $message->setNotification(new Notification($title, $body));

        $response = $this->client->send($message);
        var_dump($response->getStatusCode());
    }

    public static function subscribeTopic($topic, $token)
    {
        $url =
            'https://iid.googleapis.com/iid/v1/' .
            $token .
            '/rel/topics/' .
            $topic;
        $headers = array(
            'Authorization: key=' . self::API_KEY,
            'Content-Length: 0',
            'Content-Type:application/json'
        );
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);
        curl_close($ch);
    }
}
