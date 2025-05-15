<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    protected $fillable = [
        'model_id',
        'use_case_id',
        'bot_name',
        'description',
    ];

    public function model()
    {
        return $this->belongsTo(AiModel::class, 'model_id');
    }

    public function useCase()
    {
        return $this->belongsTo(UseCase::class, 'use_case_id');
    }
}