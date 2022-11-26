<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'pais'];
    protected $hidden = ['created_at', 'updated_at'];

    public function empleados () {
        return $this->hasMany(Empleado::class);
    }

}
