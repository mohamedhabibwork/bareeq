<?php

namespace App\Http\Resources\WorkerUser;

use App\Http\Resources\Plan\PlanResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Worker\WorkerResource;
use App\Models\WorkerUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin WorkerUser
 * @property-read WorkerUser $resource
 */
class WorkerUserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'after_images' => $this->resource->getRawOriginal('after_images') ? $this->after_images : [],
            'before_images' => $this->resource->getRawOriginal('before_images') ? $this->before_images : [],
            'order_status' => $this->order_status,
            'user_status' => $this->user_status,
            'user_id' => $this->user_id,
            'worker_id' => $this->worker_id,
            'plan_id' => $this->plan_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'plan' => new PlanResource($this->whenLoaded('plan')),
            'worker' => new WorkerResource($this->whenLoaded('worker')),
        ];
    }
}
