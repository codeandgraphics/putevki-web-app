<?php

namespace Backend\Plugins;

use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Notification;

class FCM {
    const API_KEY = 'AAAAE-UH2kc:APA91bHc6Xnrx3D7N1nbQtgP7SzFJbJs4RIaBgucAEibCRIxCfDUFxn_Lfx6gdEz90sLqvHdkX5kaPte0'.
                    'VzovrRlvIGEI3v4KgcPGs_1pGcwZwh9g3fi_5I_X45rEUP4UDisfgIO0xsd';

    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApiKey(self::API_KEY);
        $this->client->injectHttpClient(new \GuzzleHttp\Client());
    }

    public function send($title, $body, $deviceId) {
        $message = new Message();
        $message->addRecipient(new Device($deviceId));
        $message->setNotification(new Notification($title, $body));

        $response = $this->client->send($message);
        var_dump($response->getStatusCode());
    }
}
