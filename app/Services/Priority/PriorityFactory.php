<?php

namespace App\Services\Priority;

class PriorityFactory
{
    public static function make(string $priority): TaskPriorityInterface
    {
        return match ($priority) {
            'low' => new LowPriority(),
            'high' => new HighPriority(),
            default => new MediumPriority(),
        };
    }
}
