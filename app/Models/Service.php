<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'unit',
        'estimated_days',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'estimated_days' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Accessor untuk mendapatkan format harga yang indah
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.') . '/' . $this->unit;
    }

    // Accessor untuk mendapatkan estimasi waktu yang indah
    public function getFormattedEstimatedTimeAttribute()
    {
        if ($this->estimated_days == 1) {
            return '1 hari';
        } elseif ($this->estimated_days < 1) {
            $hours = round($this->estimated_days * 24);
            return $hours . ' jam';
        } else {
            return $this->estimated_days . ' hari';
        }
    }

    // Accessor untuk mendapatkan warna icon berdasarkan nama layanan
    public function getIconColorAttribute()
    {
        $colors = [
            'bg-blue-100 text-blue-600',
            'bg-green-100 text-green-600',
            'bg-yellow-100 text-yellow-600',
            'bg-purple-100 text-purple-600',
            'bg-red-100 text-red-600',
            'bg-indigo-100 text-indigo-600',
            'bg-pink-100 text-pink-600',
            'bg-orange-100 text-orange-600'
        ];
        
        $index = crc32($this->name) % count($colors);
        return $colors[$index];
    }

    // Accessor untuk mendapatkan icon berdasarkan nama layanan
    public function getIconAttribute()
    {
        $icons = [
            'fas fa-tshirt',      // Cuci Reguler
            'fas fa-bolt',         // Cuci Express
            'fas fa-iron',         // Cuci Setrika, Setrika Saja
            'fas fa-spray-can',    // Dry Clean
            'fas fa-bed',          // Cuci Selimut
            'fas fa-couch',        // Cuci Karpet
            'fas fa-snowflake',    // Cuci Dingin
            'fas fa-fire',         // Cuci Panas
            'fas fa-star'          // Default
        ];
        
        $name = strtolower($this->name);
        
        if (str_contains($name, 'reguler') || str_contains($name, 'cuci')) {
            return $icons[0];
        } elseif (str_contains($name, 'express') || str_contains($name, 'cepat')) {
            return $icons[1];
        } elseif (str_contains($name, 'setrika')) {
            return $icons[2];
        } elseif (str_contains($name, 'dry') || str_contains($name, 'kering')) {
            return $icons[3];
        } elseif (str_contains($name, 'selimut')) {
            return $icons[4];
        } elseif (str_contains($name, 'karpet')) {
            return $icons[5];
        } elseif (str_contains($name, 'dingin')) {
            return $icons[6];
        } elseif (str_contains($name, 'panas')) {
            return $icons[7];
        } else {
            return $icons[8];
        }
    }

    // Scope untuk layanan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk layanan berdasarkan unit
    public function scopeByUnit($query, $unit)
    {
        return $query->where('unit', $unit);
    }

    // Scope untuk layanan berdasarkan rentang harga
    public function scopeByPriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    // Method untuk mendapatkan total order (placeholder)
    public function getTotalOrdersAttribute()
    {
        return 0; // Akan diupdate setelah model Order dibuat
    }

    // Method untuk mendapatkan total pendapatan (placeholder)
    public function getTotalRevenueAttribute()
    {
        return 0; // Akan diupdate setelah model Order dibuat
    }

    // Method untuk mendapatkan rating rata-rata (placeholder)
    public function getAverageRatingAttribute()
    {
        return 4.5; // Akan diupdate setelah sistem rating dibuat
    }
} 