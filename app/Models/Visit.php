<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $primaryKey = 'visit_id';


    protected $fillable = [
        'visit_id',
        'visitor_id',
        'host_id',
        'status',
        'purpose'
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class, 'visitor_id', 'id');
    }

    public function host()
    {
        return $this->belongsTo(Host::class, 'host_id', 'host_id');
    }

}
