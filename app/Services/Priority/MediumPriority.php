<?php

namespace App\Services\Priority;

class MediumPriority implements TaskPriorityInterface
{
    public function getBadgeColor(): string
    {
        return 'yellow';
    }

    public function getReminderMessage(int $daysLeft): string
    {
        return "Perhatikan tenggat waktu! Sisa waktu pengerjaan: {$daysLeft} hari.";
    }
}
