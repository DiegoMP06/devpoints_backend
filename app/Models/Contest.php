<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contest extends Model
{
    protected $fillable = [
        'name',
        'image',
        'is_published',
        'user_id',
        'started_at',
        'ended_at',
        'is_ended',
    ];

    public static $validateMessages = [
        'name.required' => 'El Nombre es requerido',
        'name.string' => 'El Nombre es invalido',
        'name.max' => 'El Nombre es muy largo',
        'image.required' => 'La imagen es requerida',
        'image.file' => 'La imagen es invalida',
        'image.image' => 'La imagen es invalida',
        'image.max' => 'La imagen es muy pesada',
        'started_at.required' => 'La fecha de inicio es requerida',
        'started_at.date' => 'La fecha de inicio es invalida',
        'started_at.date_format' => 'La fecha de inicio es invalida',
        'ended_at.required' => 'La fecha de fin es requerida',
        'ended_at.date' => 'La fecha de fin es invalida',
        'ended_at.date_format' => 'La fecha de fin es invalida',
    ];

    public function isPublished()
    {
        return $this->is_published ? true : false;
    }

    public function canEvaluate()
    {
        $now = Carbon::now()->getTimestamp();
        $startedAt = Carbon::parse($this->started_at)->getTimestamp();
        $endedAt = Carbon::parse($this->ended_at)->getTimestamp();

        return !$this->is_ended && $startedAt <= $now && $endedAt >= $now;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class)->with(['members', 'assessments']);
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    public function evaluators()
    {
        return $this->belongsToMany(User::class, 'contest_users', 'contest_id', 'user_id')->withPivot(['id']);
    }

    public function saves()
    {
        return $this->belongsToMany(User::class, 'favorite_contests', 'contest_id', 'user_id')->withPivot(['id']);
    }

    public function clasifications()
    {
        return $this->belongsToMany(Team::class, 'podium_clasifications', 'contest_id', 'team_id')->withPivot(['id', 'position', 'points']);
    }
}
