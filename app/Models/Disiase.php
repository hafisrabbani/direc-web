<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admisi;

class Disiase extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];

    public function admisis()
    {
        return $this->hasMany(Admisi::class);
    }
}
