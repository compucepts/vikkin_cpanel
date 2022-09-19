<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginGuards extends Model
{
    protected $table = 'loginguards';
    protected $fillable = [ 'email','secret'];
}
