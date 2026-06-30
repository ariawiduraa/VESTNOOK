<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lahan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data_input'     => 'array',
        'statistik_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
