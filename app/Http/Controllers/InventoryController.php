<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventory::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('supplier', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'in_stock') {
                $query->whereColumn('quantity', '>=', 'min_quantity');
            } elseif ($request->status === 'low_stock') {
                $query->lowStock();
            } elseif ($request->status === 'out_of_stock') {
                $query->outOfStock();
            }
        }

        if ($request->filled('active')) {
            $request->boolean('active') ? $query->active() : $query->where('is_active', false);
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $inventories = $query->paginate(12);

        $stats = [
            'total' => Inventory::count(),
            'in_stock' => Inventory::whereColumn('quantity', '>=', 'min_quantity')->count(),
            'low_stock' => Inventory::lowStock()->count(),
            'out_of_stock' => Inventory::outOfStock()->count(),
        ];

        return view('inventory', compact('inventories', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Quick check to ensure migration has been run
        if (!Schema::hasTable('inventory')) {
            return response()->json([
                'success' => false,
                'message' => 'Tabel inventory tidak ditemukan. Jalankan migrasi database terlebih dahulu.',
            ], 500);
        }

        // Sanitize and normalize input
        $payload = $request->only([
            'name','sku','description','category','unit','quantity','min_quantity','cost_price','selling_price','supplier','location','is_active'
        ]);

        // Convert empty strings to null for nullable fields
        foreach (['description','supplier','location','selling_price','cost_price'] as $field) {
            if (array_key_exists($field, $payload) && ($payload[$field] === '' || $payload[$field] === null)) {
                $payload[$field] = null;
            }
        }

        // Ensure booleans
        $payload['is_active'] = $request->boolean('is_active');

        // For decimal columns that are NOT NULL with default 0 in DB, avoid sending explicit null
        foreach (['cost_price','selling_price'] as $numericNullableWithDefault) {
            if (array_key_exists($numericNullableWithDefault, $payload) && $payload[$numericNullableWithDefault] === null) {
                unset($payload[$numericNullableWithDefault]);
            }
        }

        $validator = Validator::make($payload, [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:inventory,sku',
            'description' => 'nullable|string',
            'category' => 'required|in:detergent,fabric_softener,bleach,plastic_bag,hanger,equipment,other',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'min_quantity' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $inventory = Inventory::create($payload);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item inventori berhasil ditambahkan',
                    'data' => $inventory,
                ]);
            }
            return redirect()->route('inventory.index')->with('success', 'Item inventori berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data',
                    'error' => $e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        $inventory->load(['transactions' => function ($q) {
            $q->latest('transaction_date');
        }]);
        return view('inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        // Sanitize and normalize input
        $payload = $request->only([
            'name','sku','description','category','unit','quantity','min_quantity','cost_price','selling_price','supplier','location','is_active'
        ]);

        foreach (['description','supplier','location','selling_price','cost_price'] as $field) {
            if (array_key_exists($field, $payload) && ($payload[$field] === '' || $payload[$field] === null)) {
                $payload[$field] = null;
            }
        }

        $payload['is_active'] = $request->boolean('is_active');

        // For update, replace null numeric with 0 to satisfy NOT NULL decimal columns
        foreach (['cost_price','selling_price'] as $decimalField) {
            if (array_key_exists($decimalField, $payload) && $payload[$decimalField] === null) {
                $payload[$decimalField] = 0;
            }
        }

        $validator = Validator::make($payload, [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:inventory,sku,' . $inventory->id,
            'description' => 'nullable|string',
            'category' => 'required|in:detergent,fabric_softener,bleach,plastic_bag,hanger,equipment,other',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'min_quantity' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $inventory->update($payload);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data inventori berhasil diperbarui',
                    'data' => $inventory,
                ]);
            }
            return redirect()->route('inventory.index')->with('success', 'Data inventori berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data',
                    'error' => $e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Inventory $inventory)
    {
        try {
            $inventory->delete();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item inventori berhasil dihapus',
                ]);
            }
            return redirect()->route('inventory.index')->with('success', 'Item inventori berhasil dihapus');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data',
                    'error' => $e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }

    /**
     * Get inventory statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total' => Inventory::count(),
            'in_stock' => Inventory::whereColumn('quantity', '>=', 'min_quantity')->count(),
            'low_stock' => Inventory::lowStock()->count(),
            'out_of_stock' => Inventory::outOfStock()->count(),
            'by_category' => Inventory::selectRaw('category, count(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category')
                ->toArray(),
        ];

        return response()->json($stats);
    }

    /**
     * Search inventory for autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $items = Inventory::where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'sku', 'quantity', 'unit', 'selling_price']);

        return response()->json($items);
    }
}


