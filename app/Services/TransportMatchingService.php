<?php

namespace App\Services;

use App\Models\TransportListing;
use App\Models\TransportRequest;
use Illuminate\Support\Collection;

class TransportMatchingService
{
    /**
     * Find matching transport listings for a farmer's request.
     * Returns a collection of listings with match scores.
     */
    public function findMatchesForRequest(TransportRequest $request): Collection
    {
        $request->load('destinationMarket');

        $listings = TransportListing::with('transporter')
            ->whereIn('status', ['available', 'partially_booked'])
            ->where('remaining_capacity', '>=', 0.1)
            ->where('available_date', '>=', now()->subDay())
            ->get();

        $scored = $listings->map(function (TransportListing $listing) use ($request) {
            $score = $this->calculateMatchScore($request, $listing);
            $listing->match_score = $score;
            $listing->match_details = $this->getMatchDetails($request, $listing);
            return $listing;
        });

        // Filter out very poor matches (< 10%)
        $filtered = $scored->filter(fn ($l) => $l->match_score >= 10);

        return $filtered->sortByDesc('match_score')->values();
    }

    /**
     * Find nearby transport requests for a transporter's listing.
     */
    public function findRequestsForListing(TransportListing $listing): Collection
    {
        $requests = TransportRequest::with(['farmer', 'destinationMarket'])
            ->where('status', 'pending')
            ->where('required_date', '>=', now()->subDay())
            ->get();

        $scored = $requests->map(function (TransportRequest $request) use ($listing) {
            $score = $this->calculateMatchScore($request, $listing);
            $request->match_score = $score;
            $request->match_details = $this->getMatchDetails($request, $listing);
            return $request;
        });

        $filtered = $scored->filter(fn ($r) => $r->match_score >= 10);

        return $filtered->sortByDesc('match_score')->values();
    }

    /**
     * Calculate match score (0-100) between a request and a listing.
     */
    private function calculateMatchScore(TransportRequest $request, TransportListing $listing): float
    {
        $score = 0;
        $weights = [
            'route' => 40,
            'date' => 25,
            'capacity' => 20,
            'perishable' => 15,
        ];

        // ── Route Proximity Score (40%) ──────────────────────────
        $routeScore = $this->calculateRouteScore($request, $listing);
        $score += $routeScore * ($weights['route'] / 100);

        // ── Date Match Score (25%) ───────────────────────────────
        $dateScore = $this->calculateDateScore($request, $listing);
        $score += $dateScore * ($weights['date'] / 100);

        // ── Capacity Score (20%) ─────────────────────────────────
        $capacityScore = $this->calculateCapacityScore($request, $listing);
        $score += $capacityScore * ($weights['capacity'] / 100);

        // ── Perishable Priority (15%) ────────────────────────────
        $perishableScore = $this->calculatePerishableScore($request, $listing);
        $score += $perishableScore * ($weights['perishable'] / 100);

        return round(min(100, max(0, $score)), 1);
    }

    private function calculateRouteScore(TransportRequest $request, TransportListing $listing): float
    {
        if (!$request->pickup_lat || !$listing->route_from_lat) {
            return 50; // Default middle score if no coordinates
        }

        // Calculate distance from pickup to listing start
        $pickupDistance = $this->haversineDistance(
            $request->pickup_lat, $request->pickup_lng,
            $listing->route_from_lat, $listing->route_from_lng
        );

        // Calculate distance from listing end to market
        $market = $request->destinationMarket;
        $destDistance = 0;
        if ($market && $listing->route_to_lat) {
            $destDistance = $this->haversineDistance(
                $listing->route_to_lat, $listing->route_to_lng,
                $market->lat, $market->lng
            );
        }

        $totalDistance = $pickupDistance + $destDistance;

        // Score: closer = higher score (within 500km range)
        if ($totalDistance <= 10) return 100;
        if ($totalDistance <= 50) return 90;
        if ($totalDistance <= 100) return 75;
        if ($totalDistance <= 200) return 50;
        if ($totalDistance <= 500) return 25;
        return 5;
    }

    private function calculateDateScore(TransportRequest $request, TransportListing $listing): float
    {
        $daysDiff = abs($request->required_date->diffInDays($listing->available_date));

        if ($daysDiff === 0) return 100;
        if ($daysDiff === 1) return 85;
        if ($daysDiff <= 3) return 60;
        if ($daysDiff <= 7) return 30;
        return 5;
    }

    private function calculateCapacityScore(TransportRequest $request, TransportListing $listing): float
    {
        if ($listing->remaining_capacity >= $request->quantity_tons) {
            return 100;
        }

        // Partial capacity available
        $ratio = ($listing->remaining_capacity / $request->quantity_tons) * 100;
        return max(10, $ratio);
    }

    private function calculatePerishableScore(TransportRequest $request, TransportListing $listing): float
    {
        if (!$request->is_perishable) {
            return 80; // Non-perishable gets decent baseline
        }

        // Perishable items need same-day or next-day availability
        $daysDiff = abs($request->required_date->diffInDays($listing->available_date));
        if ($daysDiff === 0) return 100;
        if ($daysDiff === 1) return 60;
        return 20;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula.
     * Returns distance in kilometers.
     */
    private function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function getMatchDetails(TransportRequest $request, TransportListing $listing): array
    {
        return [
            'route_score' => round($this->calculateRouteScore($request, $listing), 0),
            'date_score' => round($this->calculateDateScore($request, $listing), 0),
            'capacity_score' => round($this->calculateCapacityScore($request, $listing), 0),
            'perishable_score' => round($this->calculatePerishableScore($request, $listing), 0),
        ];
    }
}
