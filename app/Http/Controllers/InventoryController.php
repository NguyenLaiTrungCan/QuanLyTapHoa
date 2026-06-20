<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Http\Requests\InventoryRequest;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    /**
     * Get inventory list with low stock alert check
     * GET /api/inventory
     */
    public function index()
    {
        $inventories = Inventory::with('product')->get();

        return view('inventory.index', compact('inventories'));
    }

    /**
     * Get specific inventory details
     * GET /api/inventory/{id}
     */
    public function show(int $id)
    {
        $inventory = Inventory::with('product')->findOrFail($id);
        return view('inventory.edit', compact('inventory'));
    }

    /**
     * Update stock quantity
     * PUT /api/inventory/{id}
     */
    public function update(InventoryRequest $request, int $id)
    {
        $inventory = Inventory::findOrFail($id);
        
        $inventory->update([
            'quantity' => $request->quantity,
            'location' => $request->location ?? $inventory->location
        ]);

        return redirect('/inventory')->with('success', 'Đã cập nhật số lượng tồn kho thành công!');
    }

    /**
     * Decrease stock (Called by CheckoutController after successful order)
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function decreaseStock(int $productId, int $quantity): bool
    {
        $inventory = Inventory::where('product_id', $productId)->first();
        
        if ($inventory && $inventory->quantity >= $quantity) {
            $inventory->decrement('quantity', $quantity);
            return true;
        }
        
        return false;
    }

    /**
     * Increase stock (Called if order is cancelled/returned)
     * @param int $productId
     * @param int $quantity
     * @return void
     */
    public function increaseStock(int $productId, int $quantity): void
    {
        $inventory = Inventory::where('product_id', $productId)->first();
        if ($inventory) {
            $inventory->increment('quantity', $quantity);
        }
    }
}