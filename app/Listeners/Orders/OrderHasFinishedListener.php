<?php

namespace App\Listeners\Orders;

use App\Events\Orders\OrderHasFinishedEvent;
use App\Notifications\Orders\OrderHasFinishedNotification;

class OrderHasFinishedListener
{
    public function __construct()
    {
        //
    }

    public function handle(OrderHasFinishedEvent $event)
    {
        $event->getOrder()->user->notify(new OrderHasFinishedNotification($event->getOrder(),__('main.order has finish')));
        $event->getOrder()->worker->notify(new OrderHasFinishedNotification($event->getOrder(),__('main.order has finish')));
    }
}
