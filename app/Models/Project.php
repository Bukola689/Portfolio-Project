<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Project extends Model
{
    use Searchable;
    
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['name', 'keywords', 'description'];
}
