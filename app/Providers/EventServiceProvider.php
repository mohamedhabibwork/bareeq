<?php

namespace App\Providers;

use App\Events\Orders\OrderAcceptedEvent;
use App\Events\Orders\OrderCreatedEvent;
use App\Events\Orders\OrderHasFinishedEvent;
use App\Events\Orders\OrderHasStartedEvent;
use App\Listeners\Orders\OrderAcceptedListener;
use App\Listeners\Orders\OrderCreatedListener;
use App\Listeners\Orders\OrderHasFinishedListener;
use App\Listeners\Orders\OrderHasStartedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreatedEvent::class => [
            OrderCreatedListener::class,
        ],
        OrderHasFinishedEvent::class => [
            OrderHasFinishedListener::class,
        ],
        OrderHasStartedEvent::class => [
            OrderHasStartedListener::class,
        ],
        OrderAcceptedEvent::class => [
            OrderAcceptedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
