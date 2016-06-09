<?php

namespace KodiCMS\CMS\Repository;

use Illuminate\Contracts\Auth\Authenticatable;
use KodiCMS\CMS\Model\Notification;

class NotificationRepository implements \KodiCMS\CMS\Contracts\Repositories\NotificationRepository
{

    /**
     * {@inheritdoc}
     */
    public function recent(Authenticatable $user)
    {
        $notifications = Notification::with('creator')
            ->forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return $notifications;
    }

    /**
     * {@inheritdoc}
     */
    public function create(Authenticatable $user, array $data)
    {
        $creator = array_get($data, 'from');

        $notification = Notification::create([
            'user_id' => $user->id,
            'created_by' => $creator ? $creator->id : null,
            'icon' => $data['icon'],
            'body' => $data['body'],
            'action_text' => array_get($data, 'action_text'),
            'action_url' => array_get($data, 'action_url'),
        ]);

        return $notification;
    }

    /**
     * {@inheritdoc}
     */
    public function personal(Authenticatable $user, Authenticatable $from, array $data)
    {
        return $this->create($user, array_merge($data, ['from' => $from]));
    }
}
