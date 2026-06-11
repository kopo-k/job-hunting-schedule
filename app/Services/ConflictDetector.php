<?php

namespace App\Services;

class ConflictDetector
{
    private int $gapThresholdMinutes;

    public function __construct(int $gapThresholdMinutes = 60)
    {
        $this->gapThresholdMinutes = $gapThresholdMinutes;
    }

    /**
     * @param array<int, array{id:int, start:\Illuminate\Support\Carbon, end:\Illuminate\Support\Carbon}> $events
     * @return array<int, string> id => 'red'|'yellow'|'normal'
     */
    public function detect(array $events): array
    {
        $status = [];
        foreach ($events as $e) {
            $status[$e['id']] = 'normal';
        }

        $count = count($events);
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $a = $events[$i];
                $b = $events[$j];

                // 重複: a.start < b.end AND b.start < a.end
                if ($a['start']->lt($b['end']) && $b['start']->lt($a['end'])) {
                    $status[$a['id']] = 'red';
                    $status[$b['id']] = 'red';
                    continue;
                }

                // 間隔（早い方の終了〜遅い方の開始）
                if ($a['start']->lte($b['start'])) {
                    $prev = $a;
                    $next = $b;
                } else {
                    $prev = $b;
                    $next = $a;
                }
                $gap = $prev['end']->diffInMinutes($next['start'], false);
                if ($gap >= 0 && $gap < $this->gapThresholdMinutes) {
                    // redを上書きしない
                    if ($status[$prev['id']] !== 'red') {
                        $status[$prev['id']] = 'yellow';
                    }
                    if ($status[$next['id']] !== 'red') {
                        $status[$next['id']] = 'yellow';
                    }
                }
            }
        }

        return $status;
    }
}
