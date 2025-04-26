<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'exercise_id',
        'created_by',
        'deleted_by',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
