<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vms extends Model
{
    protected $fillable = [
        'lokasi',
        'status',
        'lastupdate',
        'http_link',
        'ip'
    ];

    protected $casts = [
        'status' => 'boolean',
        'lastupdate' => 'datetime'
    ];
}
