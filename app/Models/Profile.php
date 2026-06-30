<?php

namespace App\Models;

use App\Enums\GenderType;
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
        'gender' => GenderType::class,
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this?->avatar
            ? Storage::disk('public')->url($this->avatar)
            : asset('images/avatars/default-avatar.png');
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
