<?php

namespace App\Notifications\Orders;

use App\Models\WorkerUser;
use Illuminate\Notifications\Notification;

class OrderHasStartedNotification extends Notification
{
    private WorkerUser $order;
    private string $message;

    public function __construct(WorkerUser $order, string $message)
    {

        $this->order = $order;
        $this->message = $message;
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
                'message' => $this->message,
            ] + $this->order->toArray();
    }
}
