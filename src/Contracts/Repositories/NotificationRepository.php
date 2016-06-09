<?php

namespace KodiCMS\CMS\Contracts\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;
use KodiCMS\CMS\Model\Notification;

interface NotificationRepository
{

    /**
     * Get the most recent notifications for the given user.
     *
     * @param Authenticatable $user
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recent(Authenticatable $user);

    /**
     * Create an user notification.
     *
     * @param Authenticatable $user
     * @param  array          $data
     *
     * @return Notification
     */
    public function create(Authenticatable $user, array $data);

    /**
     * @param Authenticatable $user
     * @param Authenticatable $from
     * @param array           $data
     *
     * @return Notification
     */
    public function personal(Authenticatable $user, Authenticatable $from, array $data);
}
