<?php

namespace App\Models;

use App\Enums\AnimalGender;
use App\Enums\ProfileGender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'surname',
        'mobile',
        'birth_date',
        'gender',
        'address',
        'avatar',
    ];

    protected $casts = [
        'gender' => ProfileGender::class,
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->hasAvatar()
            ? Storage::disk('public')->url($this->avatar)
            : asset('images/avatars/default-avatar.png');
    }

    public function hasAvatar(): bool
    {
        return filled($this->avatar);
    }

    public function getFullNameAttribute()
    {
        return collect([$this?->first_name, $this?->surname])
            ->filter()
            ->implode(' ') ?: $this->user->name;
    }

    public function updateAttributesFromFormData(array $data): void
    {
        $formAttributes = [];

        foreach ($this->getAttributes() as $attribute => $value) {
            foreach ($data as $field => $val) {
                if ($attribute === $field) {
                    $formAttributes[$field] = $val;
                }
            }
        }

        $this->update($formAttributes);
    }

    public function createFromData(array $data)
    {
        $formAttributes = [];

        foreach ($this->getAttributes() as $attribute => $value) {
            foreach ($data as $field => $val) {
                if ($attribute === $field) {
                    $formAttributes[$field] = $val;
                }
            }
        }

        return self::query()->create($formAttributes);
    }
}
