<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'vote_id',
        'option'
    ];

    protected $table = 'options';

    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'option_users');
    }
}
