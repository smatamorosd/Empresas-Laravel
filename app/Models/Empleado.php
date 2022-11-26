<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'edad', 'empresa_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function empresa () {
        return $this->belongsTo(Empresa::class);
    }

}
