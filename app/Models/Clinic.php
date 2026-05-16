<?php

namespace App\Models;

use App\Enums\PanelRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clinic extends Model
{
    /** @use HasFactory<\Database\Factories\ClinicFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function slots(): BelongsToMany
    {
        return $this->belongsToMany(Slot::class);
    }

    public function liveStocks(): BelongsToMany
    {
        return $this->belongsToMany(Livestock::class);
    }

    public function fetchDoctors(): BelongsToMany
    {
        $doctor = Role::query()->firstWhere('name', PanelRole::Doctor);

        return $this->users()->whereHas('roles', function ($query) use ($doctor) {
            return $query->where('id', $doctor->id);
        });

    }
}
