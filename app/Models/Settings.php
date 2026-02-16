<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'user_id',
        'yandex_maps_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
