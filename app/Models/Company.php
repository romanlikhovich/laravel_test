<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table = 'companies';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'started_at',
        'id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
