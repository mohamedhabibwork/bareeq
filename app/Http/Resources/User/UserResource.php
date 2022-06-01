<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Car\CarResource;
use App\Http\Resources\Plan\PlanResource;
use App\Http\Resources\SingleRequest\SingleRequestResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Notifications\DatabaseNotificationCollection;

/** @mixin User */
class UserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'phone' => $this->phone,
            'phone_verified_at' => $this->phone_verified_at,
            'wish_day' => $this->wish_day,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'order_in_plan' => $this->order_in_plan_count ?? new MissingValue(),
            'wishing_count_sum' => $this->wishing_count_sum ?? new MissingValue(),
            'notifications' => new DatabaseNotificationCollection($this->whenLoaded('notifications')),
            'car' => new CarResource($this->whenLoaded('car')),
            'plans' => PlanResource::collection($this->whenLoaded('plans')),
            'single_requests_count' => SingleRequestResource::collection($this->whenLoaded('single_requests')),
        ];
    }
}
