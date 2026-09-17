<?php

namespace Modules\Notification\DTOs;

use Illuminate\Http\UploadedFile;
use Modules\Notification\Http\Requests\NotificationRequest;

readonly class NotificationDto
{
    public function __construct(
        public array $title,
        public array $description,
        public ?string $notifiableType = null,
        public ?int $notifiableId = null,
        public ?string $subjectType = null,
        public ?int $subjectId = null,
        public ?UploadedFile $image = null,
        public ?string $imageName = null,
        public ?string $groupBy = null,
    ) {}

    public static function fromRequest(NotificationRequest $request): self
    {
        return new self(
            title: [
                'en' => $request->validated('title_en'),
                'ar' => $request->validated('title_ar'),
            ],
            description: [
                'en' => $request->validated('description_en'),
                'ar' => $request->validated('description_ar'),
            ],
            notifiableType: $request->validated('notifiable_type'),
            notifiableId: $request->validated('notifiable_id'),
            subjectType: $request->validated('subject_type'),
            subjectId: $request->validated('subject_id'),
            image: $request->file('image'),
            groupBy: $request->validated('group_by'),
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'notifiable_type' => $this->notifiableType,
            'notifiable_id' => $this->notifiableId,
            'subject_type' => $this->subjectType,
            'subject_id' => $this->subjectId,
            'group_by' => $this->groupBy,
        ];
    }
}
