<?php

namespace App\Models;

use App\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'role', 'photo', 'sort_order'];

    protected $casts = [
        'role' => 'array',
        'sort_order' => 'integer',
    ];
}
