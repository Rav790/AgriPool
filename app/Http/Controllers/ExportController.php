<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TransportRequest;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Sanitize a CSV field to prevent CSV injection attacks.
     * Prefixes dangerous characters with a single quote.
     */
    private function sanitizeCsvField(string $value): string
    {
        $dangerousChars = ['=', '+', '-', '@', "\t", "\r"];
        if (strlen($value) > 0 && in_array($value[0], $dangerousChars)) {
            return "'" . $value;
        }
        return $value;
    }

    public function bookings(Request $request)
    {
        $user = auth()->user();

        if ($user->isFarmer()) {
            $bookings = $user->farmerBookings()->with(['transporter', 'transportRequest'])->get();
        } elseif ($user->isTransporter()) {
            $bookings = $user->transporterBookings()->with(['farmer', 'transportRequest'])->get();
        } else {
            $bookings = Booking::with(['farmer', 'transporter', 'transportRequest'])->get();
        }

        $csvData = "Booking ID,Crop,Tons,Price,Status,Payment,Farmer,Transporter,Date\n";
        foreach ($bookings as $b) {
            $csvData .= implode(',', [
                $b->id,
                $this->sanitizeCsvField($b->transportRequest->crop_type ?? 'N/A'),
                $b->allocated_tons,
                $b->total_price,
                $this->sanitizeCsvField($b->status),
                $this->sanitizeCsvField($b->payment_status),
                '"' . $this->sanitizeCsvField($b->farmer->name ?? '') . '"',
                '"' . $this->sanitizeCsvField($b->transporter->name ?? '') . '"',
                $b->created_at->format('Y-m-d'),
            ]) . "\n";
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="bookings-export-' . now()->format('Y-m-d') . '.csv"');
    }

    public function requests(Request $request)
    {
        $requests = auth()->user()->transportRequests()->with('destinationMarket')->get();

        $csvData = "Request ID,Crop,Tons,Destination,Status,Required Date,Packaging\n";
        foreach ($requests as $r) {
            $csvData .= implode(',', [
                $r->id,
                $this->sanitizeCsvField($r->crop_type),
                $r->quantity_tons,
                '"' . $this->sanitizeCsvField($r->destinationMarket->name ?? '') . '"',
                $this->sanitizeCsvField($r->status),
                $r->required_date->format('Y-m-d'),
                $this->sanitizeCsvField($r->packaging_type),
            ]) . "\n";
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="requests-export-' . now()->format('Y-m-d') . '.csv"');
    }
}

