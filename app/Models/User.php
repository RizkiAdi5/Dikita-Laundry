<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'is_active' => 'boolean',
        ];
    }

    // Relationship ke Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // ========================================================================
    // ROLE CHECKING METHODS
    // ========================================================================

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is manager
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Check if user is cashier
     */
    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    /**
     * Check if user is operator
     */
    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    /**
     * Check if user is staff
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    // ========================================================================
    // PERMISSION CHECKING METHODS
    // ========================================================================

    /**
     * Check if user can access specific menu/feature
     */
    public function canAccess(string $permission): bool
    {
        // Super admin can access everything
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Define permissions for each role
        $permissions = [
            // Dashboard - semua role
            'dashboard' => ['super_admin', 'admin', 'manager', 'cashier', 'operator', 'staff'],
            
            // Orders - semua role
            'orders' => ['super_admin', 'admin', 'manager', 'cashier', 'operator', 'staff'],
            'orders.create' => ['super_admin', 'admin', 'manager', 'cashier', 'operator', 'staff'],
            'orders.edit' => ['super_admin', 'admin', 'manager', 'cashier', 'staff'],
            'orders.delete' => ['super_admin', 'admin', 'manager'],
            
            // Customers - super_admin, admin, manager, cashier, staff
            'customers' => ['super_admin', 'admin', 'manager', 'cashier', 'staff'],
            'customers.create' => ['super_admin', 'admin', 'manager', 'cashier', 'staff'],
            'customers.edit' => ['super_admin', 'admin', 'manager', 'cashier', 'staff'],
            'customers.delete' => ['super_admin', 'admin', 'manager'],
            
            // Services - super_admin, admin, manager, staff (view only for staff)
            'services' => ['super_admin', 'admin', 'manager', 'staff'],
            'services.create' => ['super_admin', 'admin', 'manager'],
            'services.edit' => ['super_admin', 'admin', 'manager'],
            'services.delete' => ['super_admin', 'admin', 'manager'],
            
            // Inventory - super_admin, admin, manager, operator, staff (view only for staff)
            'inventory' => ['super_admin', 'admin', 'manager', 'operator', 'staff'],
            'inventory.create' => ['super_admin', 'admin', 'manager', 'operator'],
            'inventory.edit' => ['super_admin', 'admin', 'manager', 'operator'],
            'inventory.delete' => ['super_admin', 'admin', 'manager'],
            
            // Expenses - super_admin, admin, manager, staff
            'expenses' => ['super_admin', 'admin', 'manager', 'staff'],
            'expenses.create' => ['super_admin', 'admin', 'manager', 'staff'],
            'expenses.edit' => ['super_admin', 'admin', 'manager'],
            'expenses.delete' => ['super_admin', 'admin', 'manager'],
            'expenses.approve' => ['super_admin', 'admin', 'manager'],
            
            // Employees - super_admin, admin only
            'employees' => ['super_admin', 'admin'],
            'employees.create' => ['super_admin', 'admin'],
            'employees.edit' => ['super_admin', 'admin'],
            'employees.delete' => ['super_admin', 'admin'],
            
            // Reports - super_admin, admin, manager only
            'reports' => ['super_admin', 'admin', 'manager'],
            'reports.stock' => ['super_admin', 'admin', 'manager'],
            'reports.performance' => ['super_admin', 'admin', 'manager'],
            'reports.financial' => ['super_admin', 'admin', 'manager'],
        ];

        // Check if permission exists
        if (!isset($permissions[$permission])) {
            return false;
        }

        // Check if user's role has the permission
        return in_array($this->role, $permissions[$permission]);
    }

    // ========================================================================
    // HELPER METHODS
    // ========================================================================

    /**
     * Get user role label in Indonesian
     */
    public function getRoleLabelAttribute(): string
    {
        $roles = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'manager' => 'Manager',
            'cashier' => 'Kasir',
            'operator' => 'Operator',
            'staff' => 'Staff',
        ];

        return $roles[$this->role] ?? 'Unknown';
    }

    /**
     * Get all accessible menus for user
     */
    public function getAccessibleMenus(): array
    {
        $allMenus = [
            'dashboard' => [
                'name' => 'Dashboard',
                'icon' => 'fas fa-home',
                'route' => 'dashboard',
                'permission' => 'dashboard',
            ],
            'orders' => [
                'name' => 'Pesanan',
                'icon' => 'fas fa-shopping-cart',
                'route' => 'orders.index',
                'permission' => 'orders',
            ],
            'customers' => [
                'name' => 'Pelanggan',
                'icon' => 'fas fa-users',
                'route' => 'customers.index',
                'permission' => 'customers',
            ],
            'services' => [
                'name' => 'Layanan',
                'icon' => 'fas fa-concierge-bell',
                'route' => 'services.index',
                'permission' => 'services',
            ],
            'inventory' => [
                'name' => 'Inventori',
                'icon' => 'fas fa-boxes',
                'route' => 'inventory.index',
                'permission' => 'inventory',
            ],
            'employees' => [
                'name' => 'Karyawan',
                'icon' => 'fas fa-user-tie',
                'route' => 'employees.index',
                'permission' => 'employees',
            ],
            'expenses' => [
                'name' => 'Pengeluaran',
                'icon' => 'fas fa-money-bill-wave',
                'route' => 'expenses.index',
                'permission' => 'expenses',
            ],
            'reports' => [
                'name' => 'Laporan',
                'icon' => 'fas fa-chart-line',
                'route' => 'reports.index',
                'permission' => 'reports',
            ],
        ];

        $accessibleMenus = [];

        foreach ($allMenus as $key => $menu) {
            if ($this->canAccess($menu['permission'])) {
                $accessibleMenus[$key] = $menu;
            }
        }

        return $accessibleMenus;
    }
}