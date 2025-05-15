<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UseCase extends Model
{
    protected $fillable = ['name'];

    public function useCaseScores()
    {
        return $this->hasMany(ModelUseCaseScore::class, 'use_case_id');
    }
}