<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'name',
        'sku',
        'description',
        'category',
        'unit',
        'quantity',
        'min_quantity',
        'cost_price',
        'selling_price',
        'supplier',
        'location',
        'is_active',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'min_quantity' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<', 'min_quantity');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    // Accessors
    public function getFormattedCostPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->cost_price, 0, ',', '.');
    }

    public function getFormattedSellingPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->selling_price, 0, ',', '.');
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->quantity <= 0) {
            return 'out_of_stock';
        }
        if ($this->quantity < $this->min_quantity) {
            return 'low_stock';
        }
        return 'in_stock';
    }
}


