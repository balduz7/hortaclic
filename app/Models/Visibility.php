<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visibility extends Model
{
    use HasFactory;

        /**
     * 
     * Constantes que representan roles.
     * 
     */
    const PUBLIC = 1;
    const CONTACTS = 2;
    const PRIVATE  = 3;

    /**
     * 
     * Los atributos que son asignables masivamente.
     *
     */
    protected $fillable = [
        'id',
        'name',
    ];
}
