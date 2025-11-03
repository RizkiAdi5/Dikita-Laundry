<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'employee_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationship dengan Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Check if user has specific role
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Check if user is super admin
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    // Check if user can access menu
    public function canAccess($menu)
    {
        // Super admin can access everything
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Role-based menu access
        $menuPermissions = [
            'dashboard' => ['super_admin', 'admin', 'manager', 'cashier', 'operator'],
            'orders' => ['super_admin', 'admin', 'manager', 'cashier', 'operator'],
            'customers' => ['super_admin', 'admin', 'manager', 'cashier'],
            'services' => ['super_admin', 'admin', 'manager'],
            'inventory' => ['super_admin', 'admin', 'manager', 'operator'],
            'employees' => ['super_admin', 'admin'],
            'expenses' => ['super_admin', 'admin', 'manager'],
            'reports' => ['super_admin', 'admin', 'manager'],
            'reports.stock' => ['super_admin', 'admin', 'manager'],
            'reports.performance' => ['super_admin', 'admin', 'manager'],
        ];

        return isset($menuPermissions[$menu]) && in_array($this->role, $menuPermissions[$menu]);
    }
}
