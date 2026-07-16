<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicSchedule extends Model
{
    public $timestamps = false;

    /** @var list<string> */
    protected $fillable = [
        'days_of_week',
        'opens_at',
        'closes_at',
    ];

    public function clinic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function getTimeRangeAttribute(): array|string|null
    {
        return __('resources/clinics.accessors.time_range', [
            'opens'  => $this->opens_at->format('H:i'),
            'closes' => $this->closes_at->format('H:i'),
        ]);
    }

    protected function casts(): array
    {
        return [
            'days_of_week' => 'integer',
            'opens_at'     => 'datetime:H:i',
            'closes_at'    => 'datetime:H:i',
        ];
    }
}
