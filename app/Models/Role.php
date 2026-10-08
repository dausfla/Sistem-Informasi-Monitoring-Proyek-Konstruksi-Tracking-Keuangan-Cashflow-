<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function isFullAccess(): bool
    {
        return $this->name === 'full_access';
    }

    public function isPartialAccess(): bool
    {
        return $this->name === 'partial_access';
    }

    public function isRestrictedAccess(): bool
    {
        return $this->name === 'restricted_access';
    }
}
