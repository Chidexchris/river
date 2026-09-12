<?php

namespace App\Notify;

use SendGrid;
use Exception;
use Mailjet\Client;
use Mailjet\Resources;
use SendGrid\Mail\Mail;
use App\Notify\Notifiable;
use App\Notify\NotifyProcess;
use PHPMailer\PHPMailer\PHPMailer;

class Email extends NotifyProcess implements Notifiable
{
    /**
     * Email of receiver
     *
     * @var string
     */
    public string $email;

    /**
     * Assign value to properties
     *
     * @return void
     */
    public function __construct()
    {
        $this->statusField    = 'email_status';
        $this->body           = 'email_body';
        $this->globalTemplate = 'email_template';
        $this->notifyConfig   = 'mail_config';
    }

    /**
     * Send notification
     *
     * @return void
     */
    public function send(): void
    {
        // Get message from parent
        $message = $this->getMessage();

        if ($this->setting->ea && $message) {
            // Send mail
            $methodName = $this->setting->mail_config->name;
            $method     = $this->mailMethods($methodName);

            try {
                $this->$method();
            } catch (Exception $e) {
                $this->createErrorLog($e->getMessage());
                session()->flash('mail_error', $e->getMessage());
            }
        }
    }

    /**
     * Get the method name
     *
     * @param $name
     * @return string
     */
    protected function mailMethods($name): string
    {
        $methods = [
            'php'      => 'sendPhpMail',
            'smtp'     => 'sendSmtpMail',
            'sendgrid' => 'sendSendGridMail',
            'mailjet'  => 'sendMailjetMail',
        ];

        return $methods[$name];
    }

    protected function sendPhpMail(): void
    {
        $setting = $this->setting;
        $headers = "From: $setting->site_name <$setting->email_from> \r\n";
        $headers .= "Reply-To: $setting->site_name <$setting->email_from> \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";

        @mail($this->email, $this->subject, $this->finalMessage, $headers);
    }

    protected function sendSmtpMail(): void
    {
        $mail    = new PHPMailer(true);
        $setting = $this->setting;
        $config  = $setting->mail_config;

        // Server settings
        $mail->isSMTP();
        $mail->Host     = $config->host;
        $mail->SMTPAuth = true;
        $mail->Username = $config->username;
        $mail->Password = $config->password;

        if ($config->enc == 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }

        $mail->Port    = $config->port;
        $mail->CharSet = 'UTF-8';

        // Recipients
        $mail->setFrom($setting->email_from, $setting->site_name);
        $mail->addAddress($this->email, $this->receiverName);
        $mail->addReplyTo($setting->email_from, $setting->site_name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $this->subject;
        $mail->Body    = $this->finalMessage;
        $mail->send();
    }

    /**
     * @throws Exception
     */
    protected function sendSendGridMail(): void
    {
        $setting      = $this->setting;
        $sendgridMail = new Mail();

        $sendgridMail->setFrom($setting->email_from, $setting->site_name);
        $sendgridMail->setSubject($this->subject);
        $sendgridMail->addTo($this->email, $this->receiverName);
        $sendgridMail->addContent("text/html", $this->finalMessage);

        $sendgrid = new SendGrid($setting->mail_config->appkey);
        $response = $sendgrid->send($sendgridMail);

        if ($response->statusCode() != 202) {
            throw new Exception(json_decode($response->body())->errors[0]->message);
        }
    }

    protected function sendMailjetMail(): void
    {
        $setting = $this->setting;
        $mj      = new Client($setting->mail_config->public_key, $setting->mail_config->secret_key, true, ['version' => 'v3.1']);
        $body    = [
            'Messages' => [
                [
                    'From'     => [
                        'Email' => $setting->email_from,
                        'Name'  => $setting->site_name,
                    ],
                    'To'       => [
                        [
                            'Email' => $this->email,
                            'Name'  => $this->receiverName,
                        ]
                    ],
                    'Subject'  => $this->subject,
                    'TextPart' => "",
                    'HTMLPart' => $this->finalMessage,
                ]
            ]
        ];

        $mj->post(Resources::$Email, ['body' => $body]);
    }

    /**
     * Configure some properties
     *
     * @return void
     */
    public function prevConfiguration(): void
    {
        if ($this->user) {
            $this->email        = $this->user->email ?? $this->user->email_address;
            $this->receiverName = $this->user->fullname ?? $this->user->name;
        }

        $this->toAddress = $this->email;
    }
}
