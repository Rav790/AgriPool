<!-- Weather Widget — uses OpenMeteo free API (no key needed) -->
<div x-data="weatherWidget()" x-init="fetchWeather()" class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl p-5 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 opacity-20 text-8xl -mt-2 -mr-2" x-text="icon">🌤️</div>
    <div class="relative z-10">
        <p class="text-xs font-medium uppercase tracking-wider opacity-80">{{ __('Weather Today') }}</p>
        <div class="flex items-end gap-3 mt-2">
            <p class="text-4xl font-bold" x-text="temp + '°C'">--°C</p>
            <div class="text-sm opacity-90 mb-1">
                <p x-text="condition">{{ __('Loading...') }}</p>
                <p class="text-xs opacity-70" x-text="'💧 ' + humidity + '%  |  💨 ' + wind + ' km/h'"></p>
            </div>
        </div>
        <p class="text-xs mt-2 opacity-60" x-text="location">📍</p>
    </div>
</div>

<script>
function weatherWidget() {
    return {
        temp: '--',
        condition: '{{ __("Loading...") }}',
        humidity: '--',
        wind: '--',
        icon: '🌤️',
        location: '',
        fetchWeather() {
            // Default to Delhi coordinates if geolocation not available
            const lat = 28.6139;
            const lon = 77.2090;

            fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code&timezone=auto`)
                .then(r => r.json())
                .then(data => {
                    if(data.current) {
                        this.temp = Math.round(data.current.temperature_2m);
                        this.humidity = data.current.relative_humidity_2m;
                        this.wind = Math.round(data.current.wind_speed_10m);
                        this.location = '📍 Delhi, India';

                        const code = data.current.weather_code;
                        if(code <= 1) { this.condition = '{{ __("Clear Sky") }}'; this.icon = '☀️'; }
                        else if(code <= 3) { this.condition = '{{ __("Partly Cloudy") }}'; this.icon = '⛅'; }
                        else if(code <= 48) { this.condition = '{{ __("Foggy") }}'; this.icon = '🌫️'; }
                        else if(code <= 67) { this.condition = '{{ __("Rainy") }}'; this.icon = '🌧️'; }
                        else if(code <= 77) { this.condition = '{{ __("Snowy") }}'; this.icon = '❄️'; }
                        else if(code <= 82) { this.condition = '{{ __("Showers") }}'; this.icon = '🌦️'; }
                        else { this.condition = '{{ __("Stormy") }}'; this.icon = '⛈️'; }
                    }
                })
                .catch(() => {
                    this.condition = '{{ __("Unavailable") }}';
                    this.temp = '--';
                });
        }
    };
}
</script>
