<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Sửa sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width: 900px;">
    <h1 class="h3 mb-3">Sửa sản phẩm #{{ $product->id }}</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="card card-body shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label" for="name">Tên sản phẩm</label>
            <input class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="price">Giá (VND)</label>
                <input class="form-control" id="price" name="price" type="number" min="0" value="{{ old('price', $product->price) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="category_id">Danh mục</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label" for="description">Mô tả</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mt-3">
            <label class="form-label" for="image">Ảnh sản phẩm mới</label>
            <input class="form-control" id="image" name="image" type="file" accept="image/*">
            @if($product->image)
                <p class="small text-muted mt-2 mb-0">Ảnh hiện tại:</p>
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-width: 180px;" class="mt-1 rounded border">
            @endif
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </form>
</div>
</body>
</html>
