<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = [
        'classroom_id',
        'student_id',
        'last_name',
        'first_name',
        'middle_initial',
        'course',
        'year',
        'block',
        'laboratory_grade',
        'lecture_grade',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function getFullNameAttribute(): string
    {
        $mi = $this->middle_initial ? ' ' . $this->middle_initial . '.' : '';
        return "{$this->last_name}, {$this->first_name}{$mi}";
    }

    public function getAverageGradeAttribute(): float|null
    {
        if ($this->laboratory_grade !== null && $this->lecture_grade !== null) {
            return ($this->laboratory_grade + $this->lecture_grade) / 2;
        }
        return null;
    }
}