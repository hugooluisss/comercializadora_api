<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'firstname',
        'lastname',
        'gender',
        'birtday',
        'phone_movil',
        'phone_ofi',
        'address',
        'address_2',
        'between_street',
        'suburb',
        'municipality',
        'state',
        'confirmed'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'confirmed'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'birtday' => 'date'
    ];

    public function shops(){
        return $this->belongsToMany(Shop::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
