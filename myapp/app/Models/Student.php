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

    // Lecture 40% + Laboratory 60%, rounded to nearest whole number:
    public function getFinalGradeAttribute(): float|null
    {
        if ($this->laboratory_grade !== null && $this->lecture_grade !== null) {
            $raw = ($this->lecture_grade * 0.40) + ($this->laboratory_grade * 0.60);
            return round($raw); // rounds to nearest whole number
        }
        return null;
    }
}