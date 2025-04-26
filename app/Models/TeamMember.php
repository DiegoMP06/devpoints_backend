<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'father_last_name',
        'mother_last_name',
        'team_id',
    ];

    public static $validateMessages = [
        'name.required' => 'El nombre es requerido',
        'name.string' => 'El nombre debe ser una cadena de texto',
        'name.max' => 'El nombre debe tener máximo 100 caracteres',
        'father_last_name.required' => 'El apellido paterno es requerido',
        'father_last_name.string' => 'El apellido paterno debe ser una cadena de texto',
        'father_last_name.max' => 'El apellido paterno debe tener máximo 100 caracteres',
        'mother_last_name.required' => 'El apellido materno es requerido',
        'mother_last_name.string' => 'El apellido materno debe ser una cadena de texto',
        'mother_last_name.max' => 'El apellido materno debe tener máximo 100 caracteres',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
