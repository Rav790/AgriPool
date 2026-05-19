<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KycStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'kyc_update',
            'message' => $this->message,
        ];
    }
}
