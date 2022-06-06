<?php

namespace App\Listeners\Orders;

use App\Events\Orders\OrderAcceptedEvent;
use App\Notifications\Orders\OrderAcceptedNotification;

class OrderAcceptedListener
{
    public function __construct()
    {
        //
    }

    public function handle(OrderAcceptedEvent $event)
    {
        $event->getOrder()->worker->notify(new OrderAcceptedNotification($event->getOrder()));
    }
}
