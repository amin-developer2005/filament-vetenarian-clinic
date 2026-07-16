<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicAddress extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'country',
        'province',
        'city',
        'address',
        'postal_code',
        'latitude',
        'longitude',
    ];

    public function clinic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function getCoordinateAttribute(): ?array
    {
        if (blank($this->latitude) || blank($this->longitude)) {
            return null;
        }

        return [
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude
        ];
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'latitude'  => 'float',
            'longitude' => 'float',
        ];
    }
}
