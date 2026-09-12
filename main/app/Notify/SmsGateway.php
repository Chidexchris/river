<?php

namespace App\Notify;

use App\Lib\CurlRequest;
use Psr\Http\Client\ClientExceptionInterface;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;
use Vonage\Client as NexmoClient;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Exception\Exception;
use Vonage\SMS\Message\SMS;

class SmsGateway
{
    /**
     * the number where the sms will send
     *
     * @var string
     */
    public string $to;

    /**
     * the name where from the sms will send
     *
     * @var string
     */
    public string $from;

    /**
     * the message which will be sent
     *
     * @var string
     */
    public string $message;

    /**
     * the configuration of sms gateway
     *
     * @var object
     */
    public object $config;

    /**
     * @throws Exception
     * @throws ClientExceptionInterface
     */
    public function nexmo(): void
    {
        $basic    = new Basic($this->config->nexmo->api_key, $this->config->nexmo->api_secret);
        $client   = new NexmoClient($basic);
        $response = $client->sms()->send(
            new SMS($this->to, $this->from, $this->message)
        );

        $response->current();
    }

    /**
     * @throws ConfigurationException
     * @throws TwilioException
     */
    public function twilio(): void
    {
        $account_sid   = $this->config->twilio->account_sid;
        $auth_token    = $this->config->twilio->auth_token;
        $twilio_number = $this->config->twilio->from;

        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            '+' . $this->to,
            array(
                'from' => $twilio_number,
                'body' => $this->message
            )
        );
    }

    public function custom(): void
    {
        $credential = $this->config->custom;
        $method     = $credential->method;
        $shortCodes = [
            '{{message}}' => $this->message,
            '{{number}}'  => $this->to,
        ];
        $body       = array_combine($credential->body->name, $credential->body->value);

        foreach ($body as $key => $value) {
            $bodyData   = str_replace($value, $shortCodes[$value] ?? $value, $value);
            $body[$key] = $bodyData;
        }

        $header = array_combine($credential->headers->name, $credential->headers->value);

        if ($method == 'get') {
            $credential->url = $credential->url . '?' . http_build_query($body);

            CurlRequest::curlContent($credential->url, $header);
        } else {
            CurlRequest::curlPostContent($credential->url, $body, $header);
        }
    }
}
