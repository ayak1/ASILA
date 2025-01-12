<?php
use App\Models\Activity;

function transformActivities($activities) {
    return $activities->map(function ($activity) {
        return [
            'id' => $activity->id,
            'name' => $activity->translations->first()->name,
            'description' => $activity->translations->first()->full_description,
            'image' => asset('storage/' . $activity->images->firstWhere('is_cover', true)->path),
        ];
    });
}
