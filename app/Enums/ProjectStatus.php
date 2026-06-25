<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case WAITING = 'waiting';
    case IN_DEVELOPMENT = 'in_development';
    case SUSPENDED = 'suspended';
    case CLOSE_SUSPENDED = 'close_suspended';
    case UAT_TESTING = 'uat_testing';
    case READY_FOR_PRODUCTION = 'ready_for_production';
    case PRODUCTION = 'production';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::WAITING => 'Antrean Pengerjaan (Waiting)',
            self::IN_DEVELOPMENT => 'Sedang Dikoding (In Dev)',
            self::SUSPENDED => 'Ditangguhkan Sementara (Suspended)',
            self::CLOSE_SUSPENDED => 'Ditangguhkan Darurat (Close Suspended)',
            self::UAT_TESTING => 'Tahap Pengujian (UAT Testing)',
            self::READY_FOR_PRODUCTION => 'Siap Rilis (Ready For Production)',
            self::PRODUCTION => 'Sudah Rilis (Production)',
            self::CLOSED => 'Selesai & Arsip (Closed)',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::WAITING => 'bg-yellow-100 text-yellow-800 border-yellow-500',
            self::IN_DEVELOPMENT => 'bg-blue-100 text-blue-800 border-blue-500',
            self::SUSPENDED => 'bg-gray-100 text-gray-800 border-gray-500',
            self::CLOSE_SUSPENDED => 'bg-red-100 text-red-800 border-red-500',
            self::UAT_TESTING => 'bg-purple-100 text-purple-800 border-purple-500',
            self::READY_FOR_PRODUCTION => 'bg-orange-100 text-orange-800 border-orange-500',
            self::PRODUCTION => 'bg-teal-100 text-teal-800 border-teal-500',
            self::CLOSED => 'bg-green-100 text-green-800 border-green-500',
        };
    }
}
