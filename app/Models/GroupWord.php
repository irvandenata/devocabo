<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupWord extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function words()
    {
        return $this->hasMany(Word::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
