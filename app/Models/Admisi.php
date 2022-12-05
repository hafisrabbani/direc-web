<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admisi extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function disiase()
    {
        return $this->belongsTo(Disiase::class, 'id_disiase', 'id');
    }
}
