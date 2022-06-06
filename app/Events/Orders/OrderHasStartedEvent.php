<?php

namespace App\Events\Orders;

use App\Models\WorkerUser;
use Illuminate\Foundation\Events\Dispatchable;

class OrderHasStartedEvent
{
    use Dispatchable;

    private WorkerUser $order;

    public function __construct(WorkerUser $order)
    {
        $this->order = $order;
    }

    /**
     * @return WorkerUser
     */
    public function getOrder(): WorkerUser
    {
        return $this->order;
    }


}
