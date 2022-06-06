<?php

namespace App\Notifications\Orders;

use App\Models\WorkerUser;
use Illuminate\Notifications\Notification;

class OrderAcceptedNotification extends Notification
{
    private WorkerUser $order;

    public function __construct(WorkerUser $order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return $this->toArray($notifiable);
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => __('main.user_has_accept_order'),
        ] + $this->order->toArray();
    }
}
