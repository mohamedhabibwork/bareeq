<?php

namespace App\Broadcasting;

use App\Models\Base\BaseAuth;
use Http;
use Illuminate\Notifications\Notification;

class FCMChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param BaseAuth $notifiable
     * @param Notification $notification
     * @return array|null|void
     */
    public function send($notifiable, Notification $notification)
    {

        $to = $notifiable->routeNotificationFor('fcm', $notification);

        if (blank($to)) return;

        $message = $notification->toFcm($notifiable);

        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = config('fcm.server_key');

        $data = [
            "registration_ids" => array_values(array_filter($to, fn($v) => !in_array($v, ['none', '_firebaseClient.notificationToken', '32123132132']) || strlen($v) > 100)),
            "notification" => [
                "title" => $message['title'] ?? config('app.name'),
                "body" => $message['body'],
            ]
        ];


        return Http::withHeaders(['Authorization' => "key={$serverKey}"])->acceptJson()->asJson()->post($url, $data)->json();
    }
}
