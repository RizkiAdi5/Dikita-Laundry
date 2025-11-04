<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->byRole($request->role);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $employees = $query->paginate(10);

        // Statistik untuk cards
        $stats = [
            'total' => Employee::count(),
            'active' => Employee::active()->count(),
            'managers' => Employee::byRole('manager')->count(),
            'avg_salary' => Employee::avg('salary') ?? 0
        ];

        return view('employees', compact('employees', 'stats'));
    }

    /**
     * Get linked user account info for an employee
     */
    public function getAccount(Employee $employee)
    {
        $user = User::where('employee_id', $employee->id)->first();

        return response()->json([
            'success' => true,
            'data' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ] : null
        ]);
    }

    /**
     * Assign or update user account for an employee, including role and password
     */
    public function assignAccount(Request $request, Employee $employee)
    {
        $user = User::where('employee_id', $employee->id)->first();

        $rules = [
            'email' => 'required|email|max:255' . ($user ? '|unique:users,email,' . $user->id : '|unique:users,email'),
            'role' => 'required|in:super_admin,admin,manager,cashier,operator,staff',
            'password' => 'nullable|min:8|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if (!$user) {
                $user = new User();
                $user->name = $employee->name;
                $user->employee_id = $employee->id;
            }

            $user->email = $request->email;
            $user->role = $request->role;
            // is_active is not defined on users table; skip toggling active state

            if ($request->filled('password')) {
                // 'password' field cast will hash automatically
                $user->password = $request->password;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Akun login karyawan berhasil disimpan',
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan akun',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Base rules for employee creation
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email|max:255',
            'phone' => 'required|string|unique:employees,phone|max:20',
            'position' => 'required|string|max:255',
            'role' => 'required|in:admin,manager,cashier,operator,delivery',
            'hire_date' => 'required|date|before_or_equal:today',
            'salary' => 'nullable|numeric|min:0',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date|before:today',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ];

        // Optional: create linked login account during employee creation
        if ($request->boolean('create_login')) {
            // Ensure employee email also unique in users when creating login
            $rules['email'] = 'required|email|max:255|unique:employees,email|unique:users,email';
            $rules['login_role'] = 'required|in:super_admin,admin,manager,cashier,operator,staff';
            $rules['login_password'] = 'required|string|min:8|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $employee = Employee::create($request->all());

            // Create linked user login if requested
            if ($request->boolean('create_login')) {
                User::create([
                    'name' => $employee->name,
                    'email' => $request->input('email'),
                    'role' => $request->input('login_role', 'cashier'),
                    'password' => $request->input('login_password'), // hashed by casts
                    'employee_id' => $employee->id,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Karyawan berhasil ditambahkan',
                'data' => $employee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:employees,email,' . $employee->id,
            'phone' => 'required|string|max:20|unique:employees,phone,' . $employee->id,
            'position' => 'required|string|max:255',
            'role' => 'required|in:admin,manager,cashier,operator,delivery',
            'hire_date' => 'required|date|before_or_equal:today',
            'salary' => 'nullable|numeric|min:0',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date|before:today',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $employee->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Data karyawan berhasil diperbarui',
                'data' => $employee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();

            return response()->json([
                'success' => true,
                'message' => 'Karyawan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get employee statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total' => Employee::count(),
            'active' => Employee::active()->count(),
            'managers' => Employee::byRole('manager')->count(),
            'avg_salary' => Employee::avg('salary') ?? 0,
            'role_distribution' => Employee::selectRaw('role, count(*) as count')
                ->groupBy('role')
                ->pluck('count', 'role')
                ->toArray(),
            'salary_ranges' => [
                'low' => Employee::where('salary', '<=', 3000000)->count(),
                'medium' => Employee::whereBetween('salary', [3000001, 5000000])->count(),
                'high' => Employee::where('salary', '>', 5000000)->count()
            ]
        ];

        return response()->json($stats);
    }

    /**
     * Search employees for autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $employees = Employee::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'email', 'phone', 'position']);

        return response()->json($employees);
    }

    /**
     * Toggle employee status
     */
    public function toggleStatus(Employee $employee)
    {
        try {
            $employee->update(['is_active' => !$employee->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Status karyawan berhasil diubah',
                'data' => $employee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}