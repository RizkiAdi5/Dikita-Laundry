<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'gender',
        'birth_date',
        'membership_type',
        'points',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
        'points' => 'integer'
    ];

    // Accessor untuk mendapatkan usia
    public function getAgeAttribute()
    {
        if ($this->birth_date) {
            return $this->birth_date->age;
        }
        return null;
    }

    // Accessor untuk mendapatkan inisial nama
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }

    // Accessor untuk mendapatkan warna avatar berdasarkan nama
    public function getAvatarColorAttribute()
    {
        $colors = [
            'bg-blue-100 text-blue-600',
            'bg-green-100 text-green-600',
            'bg-purple-100 text-purple-600',
            'bg-yellow-100 text-yellow-600',
            'bg-red-100 text-red-600',
            'bg-indigo-100 text-indigo-600',
            'bg-pink-100 text-pink-600',
            'bg-gray-100 text-gray-600'
        ];
        
        $index = crc32($this->name) % count($colors);
        return $colors[$index];
    }

    // Scope untuk pelanggan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk pelanggan berdasarkan tipe membership
    public function scopeByMembershipType($query, $type)
    {
        return $query->where('membership_type', $type);
    }

    // Method untuk mendapatkan total transaksi (placeholder)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getTotalTransactionsAttribute()
    {
        return $this->orders()->count();
    }

    // Method untuk mendapatkan total pendapatan (placeholder)
    public function getTotalRevenueAttribute()
    {
        return (float) $this->orders()->sum('total');
    }

    // Method untuk mendapatkan tanggal terakhir transaksi (placeholder)
    public function getLastTransactionDateAttribute()
    {
        $last = $this->orders()->latest('created_at')->value('created_at');
        return $last ? $last : null;
    }
} 