<?php

namespace Modules\Notification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Client\Models\Client;
use Modules\Notification\DTOs\NotificationDto;
use Modules\Notification\Services\NotificationService;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public mixed $clients,
        public array $data
    ) {}

    public function handle(NotificationService $notificationService): void
    {
        $tokens = [];

        foreach ($this->clients as $client) {
            $notificationService->save(new NotificationDto(
                title: $this->data['title'] ?? [],
                description: $this->data['description'] ?? [],
                notifiableType: Client::class,
                notifiableId: $client->id ?? $client,
                imageName: $this->data['image'] ?? null,
                groupBy: isset($this->data['group_by']) ? (string) $this->data['group_by'] : null,
            ));

            if (! empty($client->fcm_token)) {
                $tokens[] = $client->fcm_token;
            }
        }

        // Send FCM notification if FCM helper exists
        if (! empty($tokens) && class_exists('Modules\Common\Helpers\FCMService')) {
            // app('Modules\Common\Helpers\FCMService')->sendNotification($this->data, $tokens);
        }
    }
}
