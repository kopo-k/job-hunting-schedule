<?php

namespace Tests\Unit;

use App\Services\ConflictDetector;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class ConflictDetectorTest extends TestCase
{
    private function ev(int $id, string $start, string $end): array
    {
        return ['id' => $id, 'start' => Carbon::parse($start), 'end' => Carbon::parse($end)];
    }

    public function test_overlapping_events_are_red(): void
    {
        $detector = new ConflictDetector();
        $result = $detector->detect([
            $this->ev(1, '2026-06-10 14:00', '2026-06-10 15:00'),
            $this->ev(2, '2026-06-10 14:30', '2026-06-10 15:30'),
        ]);

        $this->assertSame('red', $result[1]);
        $this->assertSame('red', $result[2]);
    }

    public function test_tight_gap_is_yellow(): void
    {
        $detector = new ConflictDetector();
        $result = $detector->detect([
            $this->ev(1, '2026-06-10 14:00', '2026-06-10 15:00'),
            $this->ev(2, '2026-06-10 15:30', '2026-06-10 16:30'), // 間隔30分 < 60
        ]);

        $this->assertSame('yellow', $result[1]);
        $this->assertSame('yellow', $result[2]);
    }

    public function test_wide_gap_is_normal(): void
    {
        $detector = new ConflictDetector();
        $result = $detector->detect([
            $this->ev(1, '2026-06-10 14:00', '2026-06-10 15:00'),
            $this->ev(2, '2026-06-10 17:00', '2026-06-10 18:00'), // 間隔120分
        ]);

        $this->assertSame('normal', $result[1]);
        $this->assertSame('normal', $result[2]);
    }

    public function test_red_takes_priority_over_yellow(): void
    {
        $detector = new ConflictDetector();
        // 1と2は重複(red)、2と3は間隔短い(yellow候補) → 2はredが優先
        $result = $detector->detect([
            $this->ev(1, '2026-06-10 14:00', '2026-06-10 15:00'),
            $this->ev(2, '2026-06-10 14:30', '2026-06-10 15:30'),
            $this->ev(3, '2026-06-10 16:00', '2026-06-10 17:00'), // 2の終了から30分
        ]);

        $this->assertSame('red', $result[1]);
        $this->assertSame('red', $result[2]);
        $this->assertSame('yellow', $result[3]);
    }
}
