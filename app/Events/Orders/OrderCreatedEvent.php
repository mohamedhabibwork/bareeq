<?php

namespace App\Events\Orders;

use App\Models\WorkerUser;
use Illuminate\Foundation\Events\Dispatchable;

class OrderCreatedEvent
{
    use Dispatchable;

    private WorkerUser $workerUser;

    public function __construct(WorkerUser $workerUser)
    {
        $this->workerUser = $workerUser;
    }

    /**
     * @return WorkerUser
     */
    public function getWorkerUser(): WorkerUser
    {
        return $this->workerUser;
    }

}
