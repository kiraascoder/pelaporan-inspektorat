<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimInvestigasi extends Model
{
    protected $table = 'tim_investigasi';

    protected $primaryKey = 'tim_id';

    protected $fillable = [
        'ketua_tim_id',
    ];
}
