<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsCode extends Model
{
    protected $fillable = ['code','mobile','expire_at','status'];
}
