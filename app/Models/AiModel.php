<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiModel extends Model
{
    protected $fillable = ['name'];

    public function useCaseScores()
    {
        return $this->hasMany(ModelUseCaseScore::class, 'model_id');
    }
}
