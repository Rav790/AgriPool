<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Messaging Hub — shows all users you can message (via bookings + direct)
     */
    public function hub()
    {
        $user = auth()->user();

        // Get all users this person has exchanged messages with
        $sentToIds = Message::where('sender_id', $user->id)->pluck('receiver_id');
        $receivedFromIds = Message::where('receiver_id', $user->id)->pluck('sender_id');
        $conversationUserIds = $sentToIds->merge($receivedFromIds)->unique()->values();

        // Batch-load all conversation users in one query (prevents N+1)
        $conversationUsers = User::whereIn('id', $conversationUserIds)->get()->keyBy('id');

        // Get users with their latest message
        $conversations = [];
        foreach ($conversationUserIds as $otherId) {
            $otherUser = $conversationUsers->get($otherId);
            if (!$otherUser) continue;

            $lastMessage = Message::where(function ($q) use ($user, $otherId) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $otherId);
                })
                ->orWhere(function ($q) use ($user, $otherId) {
                    $q->where('sender_id', $otherId)->where('receiver_id', $user->id);
                })
                ->orderByDesc('created_at')
                ->first();

            $unreadCount = Message::where('sender_id', $otherId)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();

            $conversations[] = [
                'user' => $otherUser,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
            ];
        }

        // Sort by last message time (most recent first)
        usort($conversations, fn($a, $b) => ($b['last_message']->created_at ?? now())->timestamp - ($a['last_message']->created_at ?? now())->timestamp);

        // Get all farmers and transporters for the directory (so users can start new conversations)
        if ($user->isFarmer()) {
            $directory = User::where('role', 'transporter')
                ->where('is_verified', true)
                ->whereNotIn('id', $conversationUserIds)
                ->get();
        } elseif ($user->isTransporter()) {
            $directory = User::where('role', 'farmer')
                ->where('is_verified', true)
                ->whereNotIn('id', $conversationUserIds)
                ->get();
        } else {
            $directory = collect();
        }

        return view('messages.hub', compact('conversations', 'directory'));
    }

    /**
     * Direct message thread between two users (not booking-specific)
     */
    public function directThread(User $recipient)
    {
        $user = auth()->user();

        if ($recipient->id === $user->id) {
            abort(403, 'Cannot message yourself.');
        }

        // Mark unread messages from recipient as read
        Message::where('sender_id', $recipient->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where(function ($q) use ($user, $recipient) {
                $q->where('sender_id', $user->id)->where('receiver_id', $recipient->id);
            })
            ->orWhere(function ($q) use ($user, $recipient) {
                $q->where('sender_id', $recipient->id)->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('messages.direct', compact('recipient', 'messages'));
    }

    /**
     * Send a direct message (not tied to a booking)
     */
    public function directSend(Request $request, User $recipient)
    {
        $user = auth()->user();

        if ($recipient->id === $user->id) {
            abort(403);
        }

        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $recipient->id,
            'booking_id' => null,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', __('Message sent.'));
    }

    /**
     * Booking-specific chat (existing)
     */
    public function index(Booking $booking)
    {
        $user = auth()->user();

        if ($booking->farmer_id !== $user->id && $booking->transporter_id !== $user->id) {
            abort(403);
        }

        Message::where('booking_id', $booking->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $booking->messages()->with('sender')->orderBy('created_at', 'asc')->get();

        return view('messages.index', compact('booking', 'messages'));
    }

    public function store(Request $request, Booking $booking)
    {
        $user = auth()->user();

        if ($booking->farmer_id !== $user->id && $booking->transporter_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $receiverId = $booking->farmer_id === $user->id
            ? $booking->transporter_id
            : $booking->farmer_id;

        Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'booking_id' => $booking->id,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', __('Message sent.'));
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()->receivedMessages()->where('is_read', false)->count(),
        ]);
    }
}
