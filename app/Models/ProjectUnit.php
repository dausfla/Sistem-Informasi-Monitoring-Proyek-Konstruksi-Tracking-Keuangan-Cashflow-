<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'block_name',
        'unit_number',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
