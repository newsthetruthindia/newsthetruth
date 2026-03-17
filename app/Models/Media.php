<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Media extends Model
{
    use HasFactory;
    protected $table = 'medias';
    protected $fillable = [
        'type',
        'path',
        'mimetype',
        'extension',
        'url',
        'name',
        'alt',
        'description',
        'height',
        'width',
    ];
    
    /**
     * Get the clean URL without internal 'public/' prefix
     */
    public function getUrlAttribute($value)
    {
        if (empty($value)) return $value;

        // Strip redundant 'public/' from the start or /public/ from the middle
        if (str_starts_with($value, 'public/')) {
            return substr($value, 7);
        }
        
        return str_replace('/public/', '/', $value);
    }
}
