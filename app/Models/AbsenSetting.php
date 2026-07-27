<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AbsenSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_code',
        'start_time',
        'duration_minutes',
        'is_visible',
        'is_active',
        'is_always_active',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'duration_minutes' => 'integer',
        'is_visible' => 'boolean',
        'is_active' => 'boolean',
        'is_always_active' => 'boolean',
    ];

    /**
     * Check if the session is currently active and within duration.
     * If is_always_active = true, bypass all time checks.
     */
    public function checkIsActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Always active mode: skip time/duration check
        if ($this->is_always_active) {
            return true;
        }

        if (!$this->start_time) {
            return false;
        }

        $endTime = $this->start_time->copy()->addMinutes($this->duration_minutes);
        if (now()->greaterThan($endTime)) {
            // Auto turn off when duration expired
            $this->update(['is_active' => false]);
            return false;
        }

        return true;
    }

    /**
     * Get remaining seconds for the session window.
     * Returns -1 if always active (no expiry).
     */
    public function getRemainingSecondsAttribute(): int
    {
        if (!$this->is_active) {
            return 0;
        }

        // Always active = no expiry
        if ($this->is_always_active) {
            return 99999;
        }

        if (!$this->start_time) {
            return 0;
        }

        $endTime = $this->start_time->copy()->addMinutes($this->duration_minutes);
        $diff = now()->diffInSeconds($endTime, false);

        return max(0, (int) $diff);
    }
}
