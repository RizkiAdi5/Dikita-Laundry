<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'position',
        'role',
        'hire_date',
        'salary',
        'address',
        'gender',
        'birth_date',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'birth_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Accessor untuk mendapatkan usia
    public function getAgeAttribute()
    {
        if ($this->birth_date) {
            return $this->birth_date->age;
        }
        return null;
    }

    // Relationship ke User (akun login terkait karyawan)
    public function user()
    {
        return $this->hasOne(User::class);
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
            'bg-orange-100 text-orange-600'
        ];
        
        $index = crc32($this->name) % count($colors);
        return $colors[$index];
    }

    // Accessor untuk mendapatkan format gaji yang indah
    public function getFormattedSalaryAttribute()
    {
        if ($this->salary) {
            return 'Rp ' . number_format($this->salary, 0, ',', '.');
        }
        return 'Belum ditentukan';
    }

    // Accessor untuk mendapatkan lama kerja
    public function getWorkDurationAttribute()
    {
        if ($this->hire_date) {
            $now = now();
            $diff = $this->hire_date->diff($now);
            
            if ($diff->y > 0) {
                return $diff->y . ' tahun ' . $diff->m . ' bulan';
            } elseif ($diff->m > 0) {
                return $diff->m . ' bulan ' . $diff->d . ' hari';
            } else {
                return $diff->d . ' hari';
            }
        }
        return null;
    }

    // Accessor untuk mendapatkan warna role
    public function getRoleColorAttribute()
    {
        $colors = [
            'admin' => 'bg-red-100 text-red-800',
            'manager' => 'bg-purple-100 text-purple-800',
            'cashier' => 'bg-blue-100 text-blue-800',
            'operator' => 'bg-yellow-100 text-yellow-800',
            'delivery' => 'bg-indigo-100 text-indigo-800'
        ];
        
        return $colors[$this->role] ?? 'bg-gray-100 text-gray-800';
    }

    // Accessor untuk mendapatkan label role
    public function getRoleLabelAttribute()
    {
        $labels = [
            'admin' => 'Admin',
            'manager' => 'Manager',
            'cashier' => 'Cashier',
            'operator' => 'Operator',
            'delivery' => 'Delivery'
        ];
        
        return $labels[$this->role] ?? 'Unknown';
    }

    // Scope untuk karyawan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk karyawan berdasarkan role
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Scope untuk karyawan berdasarkan posisi
    public function scopeByPosition($query, $position)
    {
        return $query->where('position', 'like', "%{$position}%");
    }

    // Relationship dengan Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Method untuk mendapatkan total order yang ditangani (placeholder)
    public function getTotalOrdersAttribute()
    {
        return 0; // Akan diupdate setelah model Order dibuat
    }

    // Method untuk mendapatkan total pendapatan yang dihasilkan (placeholder)
    public function getTotalRevenueAttribute()
    {
        return 0; // Akan diupdate setelah model Order dibuat
    }

    // Method untuk mendapatkan format total pendapatan
    public function getFormattedTotalRevenueAttribute()
    {
        return 'Rp ' . number_format($this->total_revenue, 0, ',', '.');
    }

    // Method untuk mendapatkan rating kinerja (placeholder)
    public function getPerformanceRatingAttribute()
    {
        return 4.5; // Akan diupdate setelah sistem rating dibuat
    }
}