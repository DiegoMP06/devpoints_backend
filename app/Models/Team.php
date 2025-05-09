<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'contest_id',
    ];

    public static $validateMessages = [
        'name.required' => 'El nombre es requerido',
        'name.string' => 'El nombre debe ser una cadena de texto',
        'name.max' => 'El nombre debe tener máximo 100 caracteres',
        'members.required' => 'Los miembros son requeridos',
        'members.array' => 'Los miembros deben ser un arreglo',
        'members.min' => 'Los miembros deben tener al menos 1 miembro',
        'members.max' => 'Los miembros deben tener máximo 10 miembros',
        'members.*.name.required' => 'El nombre es requerido',
        'members.*.name.string' => 'El nombre debe ser una cadena de texto',
        'members.*.name.max' => 'El nombre debe tener máximo 100 caracteres',
        'members.*.father_last_name.required' => 'El apellido paterno es requerido',
        'members.*.father_last_name.string' => 'El apellido paterno debe ser una cadena de texto',
        'members.*.father_last_name.max' => 'El apellido paterno debe tener máximo 100 caracteres',
        'members.*.mother_last_name.required' => 'El apellido materno es requerido',
        'members.*.mother_last_name.string' => 'El apellido materno debe ser una cadena de texto',
        'members.*.mother_last_name.max' => 'El apellido materno debe tener máximo 100 caracteres',
    ];

    public function teamScore() {
        $suma = array_reduce($this->assessments->toArray(), function($total, $assessment) {
            return $assessment['deleted_at'] ? $total : $total + $assessment['exercise']['points'];
        }, 0);

        return $suma;
    }

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class, 'team_id', 'id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class)->withTrashed()->with(['createdBy', 'deletedBy', 'exercise']);
    }
}
