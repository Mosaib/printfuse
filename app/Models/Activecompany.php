<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activecompany extends Model
{
    protected $table = 'user_active_companies';
    public $timestamps = false;


    protected $fillable = [
        'user_id',
        'company_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'company_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
