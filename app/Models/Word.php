<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;
    protected $fillable = ['word', 'group_word_id'];
    public function groupWord()
    {
        return $this->belongsTo(GroupWord::class);
    }
}
