<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_number',
        'title',
        'description',
        'category',
        'amount',
        'payment_method',
        'status',
        'frequency',
        'employee_id',
        'approved_by',
        'receipt_number',
        'supplier',
        'expense_date',
        'due_date',
        'paid_date',
        'notes',
        'attachment_path',
        'is_recurring',
        'is_approved'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
        'is_approved' => 'boolean'
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            'paid' => 'bg-green-100 text-green-800'
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'paid' => 'Dibayar'
        ];

        return $labels[$this->status] ?? 'Tidak Diketahui';
    }

    public function getCategoryLabelAttribute()
    {
        $categories = [
            'operational' => 'Operasional',
            'utilities' => 'Utilitas',
            'salary' => 'Gaji',
            'inventory' => 'Inventaris',
            'equipment' => 'Peralatan',
            'maintenance' => 'Pemeliharaan',
            'marketing' => 'Pemasaran',
            'rent' => 'Sewa',
            'insurance' => 'Asuransi',
            'tax' => 'Pajak',
            'other' => 'Lainnya'
        ];

        return $categories[$this->category] ?? 'Tidak Diketahui';
    }

    public function getPaymentMethodLabelAttribute()
    {
        $methods = [
            'cash' => 'Tunai',
            'bank_transfer' => 'Transfer Bank',
            'card' => 'Kartu',
            'check' => 'Cek',
            'other' => 'Lainnya'
        ];

        return $methods[$this->payment_method] ?? 'Tidak Diketahui';
    }

    public function getFrequencyLabelAttribute()
    {
        $frequencies = [
            'one_time' => 'Sekali',
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan'
        ];

        return $frequencies[$this->frequency] ?? 'Tidak Diketahui';
    }

    public function getIsOverdueAttribute()
    {
        if ($this->due_date && $this->status !== 'paid') {
            return $this->due_date->isPast();
        }
        return false;
    }

    public function getDaysOverdueAttribute()
    {
        if ($this->is_overdue) {
            return $this->due_date->diffInDays(now());
        }
        return 0;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', 'paid');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('expense_date', [$startDate, $endDate]);
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereYear('expense_date', $year)
                    ->whereMonth('expense_date', $month);
    }

    // Boot method to generate expense number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expense) {
            if (empty($expense->expense_number)) {
                $expense->expense_number = 'EXP-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
} 