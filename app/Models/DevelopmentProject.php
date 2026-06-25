<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DevelopmentProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'software_request_id',
        'programmer_id',
        't_shirt_size',
        'phase_title',
        'story_points',
        'start_date',
        'end_date',
        'project_status',
        'uat_status',
        'uat_feedback',
        'is_active_load',
        'closed_at'
    ];

    protected $casts = [
        'project_status' => ProjectStatus::class,
    ];

    public function softwareRequest()
    {
        return $this->belongsTo(SoftwareRequest::class);
    }

    public function programmer()
    {
        return $this->belongsTo(Programmer::class);
    }

    public function isDelay(): bool
    {
        if ($this->project_status === ProjectStatus::CLOSED) {
            return false;
        }

        if ($this->end_date) {
            return Carbon::parse($this->end_date)->isPast() && !now()->isSameDay(Carbon::parse($this->end_date));
        }

        return false;
    }

    public function calculatePointsForMonth($month, $year): float
    {
        if (!$this->start_date || !$this->end_date || !$this->story_points) {
            return 0;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        // Total durasi pengerjaan proyek dalam hitungan hari (termasuk hari mulai)
        $totalDays = $start->diffInDays($end) + 1;
        if ($totalDays <= 0) return 0;

        // Tentukan batas awal dan akhir bulan yang sedang difilter/dilihat
        $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $monthEnd = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Cari irisan tanggal (overlap) antara durasi proyek dengan bulan filter
        $overlapStart = $start->greaterThan($monthStart) ? $start : $monthStart;
        $overlapEnd = $end->lessThan($monthEnd) ? $end : $monthEnd;

        // Jika tidak ada irisan sama sekali (proyek tidak berjalan di bulan ini)
        if ($overlapStart->greaterThan($overlapEnd)) {
            return 0;
        }

        // Hitung berapa hari proyek berjalan khusus di bulan ini
        $daysInMonth = $overlapStart->diffInDays($overlapEnd) + 1;

        // Distribusi proporsional: (Hari di bulan ini / Total Hari Proyek) * Total Story Points
        return round(($daysInMonth / $totalDays) * $this->story_points, 1);
    }
}
