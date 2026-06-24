<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'inventory']);

        if ($request->filled('search')) {
            $search = trim((string) $request->string('search'));
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $query->where('category_id', (int) $request->input('category'));
        }

        $sort = $request->input('sort');
        if ($sort === 'price_asc') {
            $query->orderBy('price');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('price');
        } elseif ($sort === 'id_asc') {
            $query->orderBy('id');
        } else {
            $query->latest('id'); // id_desc (mặc định)
        }

        if ($request->is('api/*')) {
            return response()->json($query->paginate(12));
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::query()->orderBy('name')->get();

        if ($request->is('admin/*')) {
            return view('admin.products.index', compact('products', 'categories'));
        }

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);
        $validated['image'] = $this->uploadImage($request);

        $product = Product::create($validated);

        // Tự động tạo bản ghi kho hàng với số lượng ban đầu = 0
        $product->inventory()->create(['quantity' => 0]);

        if ($request->is('api/*')) {
            return response()->json($product->load(['category', 'inventory']), 201);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Tạo sản phẩm thành công.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'inventory']);

        if (request()->is('api/*')) {
            return response()->json($product);
        }

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product->id);

        if ($request->hasFile('image')) {
            $this->deleteImage($product->image);
            $validated['image'] = $this->uploadImage($request);
        }

        $product->update($validated);

        if ($request->is('api/*')) {
            return response()->json($product->fresh()->load(['category', 'inventory']));
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:999999'],
        ]);

        $inventory = $product->inventory()->firstOrNew(['product_id' => $product->id]);
        $inventory->quantity = $validated['quantity'];
        $inventory->location = $inventory->exists ? $inventory->location : 'Kệ A';
        $inventory->save();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Đã cập nhật tồn kho cho sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        $this->deleteImage($product->image);
        $product->delete();

        if (request()->is('api/*')) {
            return response()->json(['message' => 'Deleted']);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công.');
    }

    private function validateProduct(Request $request, ?int $productId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($productId, 'id')],
            'price' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function uploadImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('products', 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
