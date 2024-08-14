<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfig extends Model
{
    use HasFactory;

    protected $table = 'email_config';

    protected $fillable = [
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'fromAddress',
        'fromName',
        'administrators',
    ];
}