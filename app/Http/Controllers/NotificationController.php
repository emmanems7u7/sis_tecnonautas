<?php

namespace App\Http\Controllers;
use App\Interfaces\NotificationInterface;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $NotificationRepository;
    public function __construct(NotificationInterface $NotificationRepository)
    {
        $this->NotificationRepository = $NotificationRepository;
    }
    public function markAsRead($notificationId)
    {

        $this->NotificationRepository->markAsRead($notificationId);


    }
}
