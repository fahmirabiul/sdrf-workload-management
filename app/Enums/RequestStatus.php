<?php

namespace App\Enums;

enum RequestStatus: string
{
    case SUBMITTED = 'submitted';
    case ANALYSIS_SCHEDULED = 'analysis_scheduled';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::SUBMITTED => 'form pengajuan diajukan oleh user dan menunggu penjadwalan analisis',
            self::ANALYSIS_SCHEDULED => 'form pengajuan telah dijadwalkan untuk dianalisis',
            self::APPROVED => 'form pengajuan telah disetujui',
            self::REJECTED => 'form pengajuan telah ditolak',
        };
    }
}
