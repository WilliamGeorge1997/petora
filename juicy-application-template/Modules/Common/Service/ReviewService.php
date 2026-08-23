<?php

namespace Modules\Common\Service;

use Modules\Common\Entities\Review;

class ReviewService
{
    public function findAll(array $data = [])
    {
        $reviews = Review::with('branch')->available()->orderByDesc('id');

        return getCaseCollection($reviews, $data);
    }

    public function save(array $data): Review
    {
        return Review::create($data);
    }

    public function findById(int $id): Review
    {
        return Review::findOrFail($id);
    }

    public function delete(int $id): void
    {
        $this->findById($id)->delete();
    }

    public function getAverageRatings(): array
    {
        $averages = Review::available()
            ->selectRaw('
                ROUND(AVG(food_quality), 1) as food_quality,
                ROUND(AVG(service_speed), 1) as service_speed,
                ROUND(AVG(staff), 1) as staff,
                ROUND(AVG(cleanliness), 1) as cleanliness,
                ROUND(AVG(will_revisit), 1) as will_revisit
            ')
            ->first();

        return [
            'food_quality' => (float) ($averages->food_quality ?? 0),
            'service_speed' => (float) ($averages->service_speed ?? 0),
            'staff' => (float) ($averages->staff ?? 0),
            'cleanliness' => (float) ($averages->cleanliness ?? 0),
            'will_revisit' => (float) ($averages->will_revisit ?? 0),
        ];
    }
}
