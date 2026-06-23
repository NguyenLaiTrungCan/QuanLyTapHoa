<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Đã tạo danh mục thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // Tối ưu: Eager load để tránh N+1 query khi hiển thị danh sách sản phẩm
        $products = $category->products()->latest()->paginate(12);
        
        // Chỉ lấy các trường cần thiết cho Sidebar và có thể cache tại đây nếu cần
        $categories = Category::orderBy('name')->get(['id', 'name']); 
        
        // Kiểm tra nếu view của Người 1 tồn tại thì mới gọi, không thì quay về index
        if (view()->exists('products.index')) {
            return view('products.index', compact('products', 'category', 'categories'));
        }

        return redirect()->route('admin.categories.index')->with('warning', 'Trang danh sách sản phẩm đang được xây dựng.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Danh mục '{$category->name}' đã được cập nhật.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('warning', 'Không thể xóa danh mục đang có sản phẩm.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Đã xóa danh mục thành công.');
    }
}
