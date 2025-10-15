<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
        use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_code',
        'name',
        'email',
        'phone',
        'division_id',
        'position_id',
        'join_date',
        'status',
        'address'
    ];

    protected $casts = [
        'join_date' => 'date',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
