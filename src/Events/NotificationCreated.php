<?php

namespace KodiCMS\CMS\Events;

use KodiCMS\CMS\Model\Notification;

class NotificationCreated
{

    /**
     * @var Notification
     */
    public $notification;

    /**
     * NotificationCreated constructor.
     *
     * @param Notification $notification
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }
}
