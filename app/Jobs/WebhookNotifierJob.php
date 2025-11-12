<?php

namespace App\Jobs;

use App\Models\Webhook;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookNotifierJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly string $event, private readonly array $payload) {}

    /**
     * Execute the job.
     *
     * @throws \Exception
     */
    public function handle(): void
    {
        $webhook = Webhook::query()->where('event', $this->event)->first();
        if ($webhook) {
            $response = Http::retry(3, 1000, function ($exception, $request) {
                Log::withContext([
                    'response' => optional($exception->response)->body(),
                ]);

                // Optionally only retry on specific status codes or conditions
                return $exception instanceof RequestException;
            })->post($webhook->delivery_url, $this->payload);

            if ($response->failed()) {
                Log::withContext([
                    'response' => $response->json(),
                ]);
                throw new \Exception("Failed to deliver {$this->event} event request!", $response->status());
            }
        }
    }
}
