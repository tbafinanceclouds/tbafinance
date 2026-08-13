<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'icon',
        'color',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
        
        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function documents()
{
    return $this->hasMany(Document::class, 'category_id');
}

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Attributes
    public function getDocumentCountAttribute()
    {
        return $this->documents()->count();
    }

    public function getFormattedColorAttribute()
    {
        return $this->color ?? '#6B7280';
    }
}