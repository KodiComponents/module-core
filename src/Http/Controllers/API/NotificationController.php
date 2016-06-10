<?php

namespace KodiCMS\CMS\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\CMS\Model\Notification;
use KodiCMS\CMS\Repository\NotificationRepository;

class NotificationController extends Controller
{
    /**
     * Get the recent notifications and announcements for the user.
     *
     * @param NotificationRepository $notifications
     * @param Request                $request
     */
    public function recent(NotificationRepository $notifications, Request $request)
    {
        $this->setContent($notifications->recent($request->user()));
    }

    /**
     * Mark the given notifications as read.
     *
     * @return Response
     */
    public function markAsRead()
    {
        $ids = $this->getParameter('ids', []);

        Notification::whereIn('id', $ids)->update(['read' => 1]);
    }
}