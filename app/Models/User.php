<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'department_title',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function projectDocuments(): HasMany
    {
        return $this->hasMany(ProjectDocument::class, 'uploaded_by');
    }

    public function isFullAccess(): bool
    {
        return $this->role ? $this->role->isFullAccess() : false;
    }

    public function isPartialAccess(): bool
    {
        return $this->role ? $this->role->isPartialAccess() : false;
    }

    public function isRestrictedAccess(): bool
    {
        return $this->role ? $this->role->isRestrictedAccess() : true;
    }

    public function canEditFinancials(): bool
    {
        return $this->isFullAccess();
    }

    public function canEditProgress(): bool
    {
        return $this->isFullAccess() || $this->isPartialAccess();
    }
}
