<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $fillable = [
        'name',
        'image',
        'is_published',
        'user_id',
    ];

    public static $validateMessages = [
        'name.required' => 'El Nombre es requerido',
        'name.string' => 'El Nombre es invalido',
        'name.max' => 'El Nombre es muy largo',
        'image.required' => 'La imagen es requerida',
        'image.file' => 'La imagen es invalida',
        'image.image' => 'La imagen es invalida',
        'image.max' => 'La imagen es muy pesada',
    ];

    public function isPublished()
    {
        return $this->is_published ? true : false;
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
}
