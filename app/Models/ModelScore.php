<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelScore extends Model
{
    protected $fillable = ['model_name', 'use_case', 'score'];
}

