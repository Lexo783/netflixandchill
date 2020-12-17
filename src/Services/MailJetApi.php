<?php


namespace App\Services;


use Mailjet\Client;
use Mailjet\Resources;

class MailJetApi
{
    private $mailJetPublicKey;
    private $mailJetSecretKey;
    private $mailjetClient;

    public function __construct($mailJetPublicKey, $mailJetSecretKey)
    {
        $this->mailJetPublicKey = $mailJetPublicKey;
        $this->mailJetSecretKey = $mailJetSecretKey;
        $this->mailjetClient = new Client($this->mailJetPublicKey,$this->mailJetSecretKey,true,['version' => 'v3.1']);
    }

    public function send($toEmail,$toName,$subject,$content)
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "studiohiddendreams@gmail.com",
                        'Name' => "test Pilote"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName,
                        ]
                    ],
                    'TemplateID' => 2060416,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $this->mailjetClient->post(Resources::$Email, ['body' => $body]);
        $response->success() ;
    }
}

