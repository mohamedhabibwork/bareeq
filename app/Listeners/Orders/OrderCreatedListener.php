<?php

namespace App\Listeners\Orders;

use App\Events\Orders\OrderCreatedEvent;
use App\Notifications\Orders\OrderCreatedNotification;

class OrderCreatedListener
{
    public function __construct()
    {
        //
    }

    public function handle(OrderCreatedEvent $event)
    {
        $event->getWorkerUser()->user->notify(new OrderCreatedNotification($event->getWorkerUser()));
    }
}
