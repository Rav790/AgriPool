<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>{{ __('Invoice') }} #{{ $booking->id }}</title>
<style>body{font-family:sans-serif;font-size:14px;color:#333;margin:40px;}
.header{display:flex;justify-content:space-between;border-bottom:2px solid #16a34a;padding-bottom:20px;margin-bottom:20px;}
.logo{font-size:24px;font-weight:bold;color:#16a34a;}
table{width:100%;border-collapse:collapse;margin:20px 0;}
th,td{padding:10px;text-align:left;border-bottom:1px solid #eee;}
th{background:#f9fafb;font-weight:600;}
.total{font-size:20px;font-weight:bold;color:#16a34a;text-align:right;margin-top:20px;}
.footer{margin-top:40px;text-align:center;color:#999;font-size:11px;}
</style></head>
<body>
<div class="header">
    <div><div class="logo">AgriPool</div><p>{{ __('Transport Sharing Platform') }}</p></div>
    <div style="text-align:right;"><p><strong>{{ __('Invoice') }} #{{ $booking->id }}</strong></p><p>{{ now()->format('M d, Y') }}</p></div>
</div>

<h3>{{ __('Booking Details') }}</h3>
<table>
<tr><th>{{ __('Crop') }}</th><td>{{ $booking->transportRequest->crop_type ?? '—' }}</td></tr>
<tr><th>{{ __('Farmer') }}</th><td>{{ $booking->farmer->name ?? '—' }}</td></tr>
<tr><th>{{ __('Transporter') }}</th><td>{{ $booking->transporter->name ?? '—' }}</td></tr>
<tr><th>{{ __('Route') }}</th><td>{{ $booking->transportListing->route_from ?? '—' }} → {{ $booking->transportListing->route_to ?? '—' }}</td></tr>
<tr><th>{{ __('Market') }}</th><td>{{ $booking->transportRequest->destinationMarket->name ?? '—' }}</td></tr>
<tr><th>{{ __('Allocated Tons') }}</th><td>{{ $booking->allocated_tons }}</td></tr>
<tr><th>{{ __('Price per Ton') }}</th><td>₹{{ number_format($booking->transportListing->price_per_ton ?? 0, 2) }}</td></tr>
<tr><th>{{ __('Payment Mode') }}</th><td>{{ strtoupper($booking->payment_mode ?? '—') }}</td></tr>
<tr><th>{{ __('Payment Status') }}</th><td>{{ ucfirst($booking->payment_status) }}</td></tr>
</table>

<div class="total">{{ __('Total') }}: ₹{{ number_format($booking->total_price, 2) }}</div>

<div class="footer">
    <p>AgriPool — {{ __('Transport Sharing Platform for Agricultural Produce') }}</p>
    <p>{{ __('This is a computer-generated invoice.') }}</p>
</div>
</body></html>
