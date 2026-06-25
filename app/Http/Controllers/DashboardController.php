<?php

namespace App\Http\Controllers;

use App\Models\Programmer;
use App\Models\DevelopmentProject;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $programmerCapacities = collect();

        // Inisialisasi variabel bulan dan tahun (Default: Bulan & Tahun Ini)
        $selectedMonth = (int) $request->input('month', now()->month);
        $selectedYear = (int) $request->input('year', now()->year);

        // Menentukan apakah Kepala TIK sedang melihat bulan berjalan atau masa lalu
        $isCurrentMonth = ($selectedMonth === now()->month && $selectedYear === now()->year);

        // Jika yang masuk adalah Kepala TIK, hitung kapasitas beban kerja dinamis berdasarkan filter
        if (auth()->check() && auth()->user()->role === 'kepala_tik') {
            $monthStart = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
            $monthEnd = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();

            $programmerCapacities = Programmer::with(['user'])->get()->map(function ($programmer) use ($selectedMonth, $selectedYear, $monthStart, $monthEnd, $isCurrentMonth) {

                // Query adaptif: Jika bulan ini, cek is_active_load. Jika history, abaikan penanda is_active_load
                $query = DevelopmentProject::where('programmer_id', $programmer->id)
                    ->whereNotNull('start_date')
                    ->whereNotNull('end_date')
                    ->where('start_date', '<=', $monthEnd)
                    ->where('end_date', '>=', $monthStart);

                if ($isCurrentMonth) {
                    $query->where('is_active_load', true);
                }

                $activeProjects = $query->get();

                // Hitung total poin secara proporsional sesuai irisan hari kerja di bulan tersebut
                $activePoints = $activeProjects->sum(function ($project) use ($selectedMonth, $selectedYear) {
                    return $project->calculatePointsForMonth($selectedMonth, $selectedYear);
                });

                $maxCapacity = $programmer->max_monthly_capacity ?? 20;

                // Hitung persentase keterisian bar kuota dashboard
                $percentage = $maxCapacity > 0 ? min(($activePoints / $maxCapacity) * 100, 100) : 0;

                return [
                    'name' => $programmer->user ? $programmer->user->name : 'Tanpa Nama',
                    'active_points' => (float) $activePoints, // Gunakan float agar angka desimal proporsional akurat
                    'max_capacity' => $maxCapacity,
                    'percentage' => $percentage,
                ];
            });
        }

        // Kirim data kapasitas beserta status filter aktif ke dalam view dashboard
        return view('dashboard', compact('programmerCapacities', 'selectedMonth', 'selectedYear'));
    }
}
