<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\httpclient\Client;

class SmsService extends Component
{
    public $apiKey;
    public $apiUrl = 'https://smspilot.ru/api.php';

    public function sendSms($phone, $message)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($this->apiUrl)
            ->setData([
                'send' => $message,
                'to' => $phone,
                'apikey' => $this->apiKey,
            ])
            ->send();

        if ($response->isOk) {
            return true;
        } else {
            Yii::error("SMS sending failed: " . $response->content);
            return false;
        }
    }
}
