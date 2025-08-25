<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'professional_url',
        'photo_path',
        'is_admin',
        'last_seen',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'last_seen' => 'datetime',
    ];

    /**
     * Get the user's profile photo URL.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->photo_path) {
            return Storage::url($this->photo_path);
        }

        // Generate avatar with initials if no photo
        $initials = $this->getInitials();
        $color = $this->getAvatarColor();
        
        return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='128' height='128' viewBox='0 0 128 128'%3E%3Crect width='128' height='128' fill='%23{$color}'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='0.3em' fill='white' font-size='48' font-weight='bold'%3E{$initials}%3C/text%3E%3C/svg%3E";
    }

    /**
     * Get user initials for avatar.
     */
    private function getInitials()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach (array_slice($names, 0, 2) as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        
        return $initials ?: 'U';
    }

    /**
     * Get avatar color based on user ID.
     */
    private function getAvatarColor()
    {
        $colors = [
            '3b82f6', // blue
            '6366f1', // indigo
            '8b5cf6', // violet
            '06b6d4', // cyan
            '10b981', // emerald
            'f59e0b', // amber
            'ef4444', // red
            'ec4899', // pink
        ];
        
        return $colors[$this->id % count($colors)];
    }

    /**
     * Get WhatsApp URL with country prefix.
     */
    public function getWhatsappUrlAttribute()
    {
        if (!$this->phone) {
            return null;
        }

        $countryPrefix = config('app.whatsapp_country_prefix', '54');
        $cleanPhone = preg_replace('/[^0-9]/', '', $this->phone);
        
        // Remove country code if already present
        if (substr($cleanPhone, 0, strlen($countryPrefix)) === $countryPrefix) {
            $cleanPhone = substr($cleanPhone, strlen($countryPrefix));
        }
        
        return "https://wa.me/{$countryPrefix}{$cleanPhone}";
    }

    /**
     * Get professional platform name.
     */
    public function getProfessionalPlatformNameAttribute()
    {
        if (!$this->professional_url) {
            return null;
        }

        if (str_contains($this->professional_url, 'linkedin.com')) {
            return 'LinkedIn';
        } elseif (str_contains($this->professional_url, 'github.com')) {
            return 'GitHub';
        } elseif (str_contains($this->professional_url, 'gitlab.com')) {
            return 'GitLab';
        } elseif (str_contains($this->professional_url, 'behance.net')) {
            return 'Behance';
        } elseif (str_contains($this->professional_url, 'dribbble.com')) {
            return 'Dribbble';
        }

        return 'Sitio Web';
    }

    /**
     * Get professional platform icon class.
     */
    public function getProfessionalIconClassAttribute()
    {
        if (!$this->professional_url) {
            return 'fas fa-link';
        }

        if (str_contains($this->professional_url, 'linkedin.com')) {
            return 'fab fa-linkedin text-blue-600';
        } elseif (str_contains($this->professional_url, 'github.com')) {
            return 'fab fa-github text-gray-800';
        } elseif (str_contains($this->professional_url, 'gitlab.com')) {
            return 'fab fa-gitlab text-orange-600';
        } elseif (str_contains($this->professional_url, 'behance.net')) {
            return 'fab fa-behance text-blue-500';
        } elseif (str_contains($this->professional_url, 'dribbble.com')) {
            return 'fab fa-dribbble text-pink-500';
        }

        return 'fas fa-external-link-alt text-blue-600';
    }

    /**
     * Get professional platform button class.
     */
    public function getProfessionalButtonClassAttribute()
    {
        if (!$this->professional_url) {
            return 'bg-blue-600 hover:bg-blue-700';
        }

        if (str_contains($this->professional_url, 'linkedin.com')) {
            return 'bg-blue-600 hover:bg-blue-700';
        } elseif (str_contains($this->professional_url, 'github.com')) {
            return 'bg-gray-800 hover:bg-gray-900';
        } elseif (str_contains($this->professional_url, 'gitlab.com')) {
            return 'bg-orange-600 hover:bg-orange-700';
        } elseif (str_contains($this->professional_url, 'behance.net')) {
            return 'bg-blue-500 hover:bg-blue-600';
        } elseif (str_contains($this->professional_url, 'dribbble.com')) {
            return 'bg-pink-500 hover:bg-pink-600';
        }

        return 'bg-blue-600 hover:bg-blue-700';
    }

    /**
     * Check if user is online (within last 5 minutes).
     */
    public function isOnline()
    {
        if (!$this->last_seen) {
            return false;
        }

        return $this->last_seen->diffInMinutes(now()) <= 5;
    }

    /**
     * Scope for searching users.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * Scope for filtering by role.
     */
    public function scopeByRole($query, $role)
    {
        if ($role === 'admin') {
            return $query->where('is_admin', true);
        } elseif ($role === 'student') {
            return $query->where('is_admin', false);
        }

        return $query;
    }

    /**
     * Update last seen timestamp.
     */
    public function updateLastSeen()
    {
        $this->update(['last_seen' => now()]);
    }
}