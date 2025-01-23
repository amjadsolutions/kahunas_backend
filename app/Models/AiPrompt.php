<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiPrompt extends Model
{
    use HasFactory;

    protected $fillable = ['user_prompt', 'ai_response', 'response_quality_score'];
}
