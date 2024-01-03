<?php

namespace App\Models;

use App\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class CaptureLinkedin extends Model
{
    use HasOwner;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'function',
        'linkedin_id',
    ];
}
