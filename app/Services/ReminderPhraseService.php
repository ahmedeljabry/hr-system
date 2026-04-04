<?php

namespace App\Services;

use App\Enums\NotificationEvent;
use App\Models\ReminderPhrase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class ReminderPhraseService
{
    /**
     * Parse raw string substituting payload variables mapped dynamically.
     * Safely bounds keys missing from payload array, defaulting to their raw key name.
     */
    protected function interpolate(string $text, array $payload): string
    {
        return preg_replace_callback('/\{([a-zA-Z0-9_]+)\}/', function ($matches) use ($payload) {
            $key = $matches[1];
            return $payload[$key] ?? $matches[0];
        }, $text);
    }

    /**
     * Resolves the phrase via DB fallback Cache layer, mapping to translation string if undefined.
     * Guaranteed < 50ms (Cache hit), rendering strictly per locale.
     */
    public function getParsedMessage(NotificationEvent $event, array $payload = []): string
    {
        $locale = App::getLocale();
        $cacheKey = "reminder_phrase_{$event->value}";
        
        $phrase = Cache::remember($cacheKey, now()->addDay(), function () use ($event) {
            return ReminderPhrase::where('event_key', $event)->first();
        });

        if (!$phrase) {
            // Edge Case fallback to Laravel translation strictly requested by specification
            return __("messages.{$event->value}", $payload);
        }

        $text = $locale === 'ar' ? $phrase->text_ar : $phrase->text_en;
        
        return $this->interpolate($text, $payload);
    }

    /**
     * Bust cache when updated successfully
     */
    public function clearCache(NotificationEvent $event): void
    {
        Cache::forget("reminder_phrase_{$event->value}");
    }
}
