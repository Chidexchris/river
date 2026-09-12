<?php

namespace App\Notify;

class Notify
{
    /*
    |--------------------------------------------------------------------------
    | Send Notification
    |--------------------------------------------------------------------------
    |
    | The notification will go via some methods which were implemented. Different
    | classes are available for a particular method. But we need a central position
    | to serve a notification via every method which is selected. This is
    | the class that is serving this perspective.
    |
    */

    /**
     * Template name, which contain the short codes and messages
     *
     * @var string
     */
    public string $templateName;

    /**
     * Short Codes, which will be replaced
     *
     * @var array
     */
    public array $shortCodes;

    /**
     * Send via email, sms etc
     *
     * @var array|null
     */
    public ?array $sendVia;

    /**
     * Instance of user, who will get the notification
     *
     * @var object
     */
    public object $user;

    /**
     * System general setting's instances
     *
     * @var object
     */
    public mixed $setting;

    /**
     * The relational field name like user_id, agent_id
     *
     * @var string
     */
    public string $userColumn;

    /**
     * Assign value to sendVia and setting property
     *
     * @param null $sendVia
     * @return void
     */
    public function __construct($sendVia = null)
    {
        $this->sendVia = $sendVia;
        $this->setting = bs();
    }

    /**
     * Send notification via methods.
     *
     * This method is creating instances of notifications to send the notification.
     *
     * @return void
     */
    public function send(): void
    {
        $methods = [];

        //get the notification method classes which are selected
        if ($this->sendVia) {
            foreach ($this->sendVia as $sendVia) {
                $methods[$sendVia] = $this->notifyMethods($sendVia);
            }
        } else {
            $methods = $this->notifyMethods();
        }

        //send the notification via methods one by one
        foreach ($methods as $method) {
            $toast               = new $method;
            $toast->templateName = $this->templateName;
            $toast->shortCodes   = $this->shortCodes;
            $toast->user         = $this->user;
            $toast->setting      = $this->setting;
            $toast->userColumn   = $this->userColumn;
            $toast->send();
        }
    }

    /**
     * Get the notification method classes.
     *
     * @param array|string|null $sendVia
     * @return array|string
     */
    protected function notifyMethods(array|string $sendVia = null): array|string
    {
        $methods = [
            'email' => Email::class,
            'sms'   => Sms::class,
        ];

        if ($sendVia) return $methods[$sendVia];

        return $methods;
    }
}
