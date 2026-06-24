<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::withCount('products')
            ->latest()
            ->get();
        
        return response()->json([
            'data' => $categories
        ]);
    }

    public function show(Category $category): JsonResponse
    {
        $category->loadCount('products');

        return response()->json([
            'data' => $category
        ]);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json([
            'message' => 'Đã tạo danh mục thành công.',
            'data' => $category->loadCount('products'),
        ], 201);
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());

        return response()->json([
            'message' => 'Đã cập nhật danh mục thành công.',
            'data' => $category->fresh()->loadCount('products'),
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->products()->exists()) {
            // Trả về lỗi 422 để frontend hiển thị thông báo "Vàng/Cảnh báo"
            return response()->json([
                'message' => 'Không thể xóa danh mục đang có sản phẩm.',
            ], 422); 
        }

        $category->delete();

        return response()->json([
            'message' => 'Đã xóa danh mục thành công.',
        ]);
    }
}
