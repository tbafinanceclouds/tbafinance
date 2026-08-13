<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'category_id',
        'related_type',
        'related_id',
        'name',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'file_type',
        'description',
        'upload_date',
        'expires_date',
        'is_verified',
        'verified_at',
        'verified_by',
        'uploaded_by'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'upload_date' => 'date',
        'expires_date' => 'date',
        'verified_at' => 'datetime',
        'file_size' => 'integer'
    ];

    // Boot method to set defaults
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($document) {
            $document->upload_date = $document->upload_date ?? now();
            $document->uploaded_by = $document->uploaded_by ?? auth()->id();
        });
    }

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    // Polymorphic relationship
    public function related()
    {
        return $this->morphTo();
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeUnverified($query)
    {
        return $query->where('is_verified', false);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_date', '<', now());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('related_type', $type);
    }

    // Attributes
    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    public function getIsImageAttribute()
    {
        return in_array($this->file_type, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
    }

    public function getIsPdfAttribute()
    {
        return $this->file_type === 'pdf';
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_date && $this->expires_date < now();
    }

    public function getIconAttribute()
    {
        $icons = [
            'pdf' => 'fa-file-pdf',
            'doc' => 'fa-file-word',
            'docx' => 'fa-file-word',
            'xls' => 'fa-file-excel',
            'xlsx' => 'fa-file-excel',
            'jpg' => 'fa-file-image',
            'jpeg' => 'fa-file-image',
            'png' => 'fa-file-image',
            'gif' => 'fa-file-image',
            'zip' => 'fa-file-archive',
            'rar' => 'fa-file-archive',
        ];
        
        return $icons[$this->file_type] ?? 'fa-file';
    }

    public function getBadgeColorAttribute()
    {
        $colors = [
            'pdf' => 'red',
            'doc' => 'blue',
            'docx' => 'blue',
            'xls' => 'green',
            'xlsx' => 'green',
            'jpg' => 'purple',
            'jpeg' => 'purple',
            'png' => 'purple',
            'zip' => 'yellow',
        ];
        
        return $colors[$this->file_type] ?? 'gray';
    }

    // Methods
    public function verify($userId)
    {
        $this->is_verified = true;
        $this->verified_at = now();
        $this->verified_by = $userId;
        $this->save();
        
        return $this;
    }

    public function unverify()
    {
        $this->is_verified = false;
        $this->verified_at = null;
        $this->verified_by = null;
        $this->save();
        
        return $this;
    }

    public function deleteFile()
    {
        if (Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }
        return $this;
    }

    public function getPreviewUrlAttribute()
    {
        if ($this->is_image) {
            return $this->file_url;
        }
        return null;
    }
}