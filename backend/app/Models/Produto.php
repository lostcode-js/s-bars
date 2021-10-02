<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produto';
    protected $primaryKey = 'cod_evento';

    protected $fillable = [
        'cod_produto',
        'nom_produto',
        'des_produto',
        'val_produto',
    ];

    protected $hidden = [
    ];
}
