<?php

namespace App\Notifications;

use App\Models\TransportRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewProduceRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public TransportRequest $request
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_request',
            'request_id' => $this->request->id,
            'message' => $this->request->farmer->name . ' posted a new request: ' . $this->request->crop_type . ' (' . $this->request->quantity_tons . ' tons)',
            'crop' => $this->request->crop_type,
            'tons' => $this->request->quantity_tons,
            'pickup' => $this->request->pickup_address,
        ];
    }
}
