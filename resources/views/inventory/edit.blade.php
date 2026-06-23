@extends('layouts.app')

@section('content')
<div class="container py-4" style="background-color: #F8FAFC; font-family: 'Poppins', sans-serif; color: #1E293B;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-white border-bottom pb-0 pt-4">
                    <h4 class="fw-bold">Cập nhật Kho hàng</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.inventory.update', $inventory) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label text-muted">Sản phẩm</label>
                            <input type="text" class="form-control" value="{{ $inventory->product->name ?? 'N/A' }}" disabled readonly>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label fw-medium">Số lượng tồn kho mới <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" 
                                   class="form-control @error('quantity') is-invalid @enderror" 
                                   value="{{ old('quantity', $inventory->quantity) }}" min="0" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="location" class="form-label fw-medium">Vị trí trong kho (Location)</label>
                            <input type="text" name="location" id="location" 
                                   class="form-control @error('location') is-invalid @enderror" 
                                   value="{{ old('location', $inventory->location) }}" placeholder="VD: Kệ A1, Dãy B...">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.inventory.index') }}" class="btn btn-light border">Hủy</a>
                            <button type="submit" class="btn text-white px-4" style="background-color: #22C55E;">
                                Lưu cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
