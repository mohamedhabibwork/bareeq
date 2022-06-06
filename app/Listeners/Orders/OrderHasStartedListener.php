<?php

namespace App\Listeners\Orders;

use App\Events\Orders\OrderHasStartedEvent;
use App\Notifications\Orders\OrderHasStartedNotification;

class OrderHasStartedListener
{
    public function __construct()
    {
        //
    }

    public function handle(OrderHasStartedEvent $event)
    {
        $event->getOrder()->user->notify(new OrderHasStartedNotification($event->getOrder(),__('main.order has start')));
        $event->getOrder()->worker->notify(new OrderHasStartedNotification($event->getOrder(),__('main.order has start')));
    }
}
