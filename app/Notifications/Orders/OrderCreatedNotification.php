<?php

namespace App\Notifications\Orders;

use App\Models\WorkerUser;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    private WorkerUser $workerUser;

    public function __construct(WorkerUser $workerUser)
    {
        $this->workerUser = $workerUser;
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
        return $this->workerUser->withoutRelations()->toArray();
    }
}
