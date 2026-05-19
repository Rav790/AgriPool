<?php

namespace App\Notifications;

use App\Models\PriceAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PriceAlertCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public PriceAlert $alert,
        public string $creatorName
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'price_alert',
            'alert_id' => $this->alert->id,
            'message' => $this->creatorName . ' set a price alert: ' . $this->alert->crop_type . ' ' . $this->alert->condition . ' ₹' . number_format($this->alert->target_price) . '/quintal',
            'crop' => $this->alert->crop_type,
            'target_price' => $this->alert->target_price,
            'condition' => $this->alert->condition,
        ];
    }
}
