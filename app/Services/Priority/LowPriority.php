<?php

namespace App\Services\Priority;

class LowPriority implements TaskPriorityInterface
{
    public function getBadgeColor(): string
    {
        return 'green';
    }

    public function getReminderMessage(int $daysLeft): string
    {
        return "Santai saja, tugas ini prioritas rendah. Sisa waktu: {$daysLeft} hari.";
    }
}
