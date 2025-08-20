<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'phone',
        'professional_url',
        'photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function getWhatsAppLinkAttribute(): string
    {
        if (!$this->phone) {
            return '';
        }
        
        $countryCode = config('app.whatsapp_country_code', '+54');
        $cleanPhone = str_replace([' ', '-', '(', ')'], '', $this->phone);
        
        if (!str_starts_with($cleanPhone, '+')) {
            $cleanPhone = $countryCode . ltrim($cleanPhone, '0');
        }
        
        return "https://wa.me/" . str_replace('+', '', $cleanPhone);
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : asset('images/default-avatar.png');
    }
}