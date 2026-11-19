<?php

namespace App\Services\Priority;

/**
 * PILAR OOP: ABSTRAKSI
 * Menyembunyikan kompleksitas implementasi. Semua class prioritas
 */
interface TaskPriorityInterface
{
    public function getBadgeColor(): string;
    public function getReminderMessage(int $daysLeft): string;
}
