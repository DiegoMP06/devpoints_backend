<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'description',
        'points',
        'contest_id',
    ];

    public static $validateMessages = [
        'name.required' => 'El nombre es requerido',
        'name.string' => 'El nombre debe ser una cadena de texto',
        'name.max' => 'El nombre debe tener máximo 100 caracteres',
        'description.required' => 'La descripción es requerida',
        'description.string' => 'La descripción debe ser una cadena de texto',
        'points.required' => 'Los puntos son requeridos',
        'points.numeric' => 'Los puntos deben ser numéricos',
        'points.integer' => 'Los puntos deben ser enteros',
        'points.min' => 'Los puntos deben ser mayores o iguales a 1',
    ];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }
}
