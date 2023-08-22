<?php

namespace App\Models\Traits;

trait RankedModel
{
    public static function bootRankedModel(): void
    {
        static::creating(
            static function (self $record) {
                if (!$record->rank) {
                    $record->rank = $record->getCurrentRank();
                }
            }
        );
    }

    protected function getCurrentRank(): int
    {
        return $this->newQuery()->max('rank') + 1;
    }
}
