<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'membership';
    protected $fillable = [
          'id',
            'plan', 'row1',   
      
        'row2','row3','row4',
    'user_id',
        'price',
    ];
}
