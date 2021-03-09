<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
    use HasFactory;

    protected $fillable = [
        'vote_id',
        'option'
    ];

    protected $table = 'options';

    public function vote()
    {
        return $this->belongsTo(Options::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
