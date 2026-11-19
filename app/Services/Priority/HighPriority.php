<?php

namespace App\Services\Priority;

class HighPriority implements TaskPriorityInterface
{
    public function getBadgeColor(): string
    {
        return 'red';
    }

    public function getReminderMessage(int $daysLeft): string
    {
        if ($daysLeft <= 2) {
            return "SEGERA KERJAKAN! Waktu hampir habis (sisa {$daysLeft} hari). Jangan ditunda!";
        }

        return "Prioritas Tinggi! Segera selesaikan dalam {$daysLeft} hari ke depan.";
    }
}
