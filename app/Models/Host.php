<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
    use HasFactory;

    protected $primaryKey = 'host_id';

    protected $fillable = [
        'host_name',
        'host_email',
        'host_phone',  
        'host_position',
        'host_department'
    ];
}
