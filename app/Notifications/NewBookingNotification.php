<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public string $customMessage = ''
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking',
            'booking_id' => $this->booking->id,
            'message' => $this->customMessage ?: 'New booking #' . $this->booking->id . ' created.',
            'crop' => $this->booking->transportRequest?->crop_type ?? '—',
            'amount' => $this->booking->total_price,
        ];
    }
}
