<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'status',
        'planned_progress',
        'actual_progress',
        'total_contract_value',
    ];

    protected $casts = [
        'planned_progress' => 'float',
        'actual_progress' => 'float',
        'total_contract_value' => 'float',
    ];

    public function projectUnits(): HasMany
    {
        return $this->hasMany(ProjectUnit::class);
    }

    public function cashflows(): HasMany
    {
        return $this->hasMany(Cashflow::class);
    }

    public function projectDocuments(): HasMany
    {
        return $this->hasMany(ProjectDocument::class);
    }

    // Calculated Variance
    public function getVarianceAttribute(): float
    {
        return round($this->actual_progress - $this->planned_progress, 2);
    }

    // Total Income
    public function getTotalIncomeAttribute(): float
    {
        return (float) $this->cashflows()->where('type', 'income')->sum('amount');
    }

    // Total Expense
    public function getTotalExpenseAttribute(): float
    {
        return (float) $this->cashflows()->where('type', 'expense')->sum('amount');
    }

    // Balance
    public function getBalanceAttribute(): float
    {
        return $this->total_income - $this->total_expense;
    }
}
