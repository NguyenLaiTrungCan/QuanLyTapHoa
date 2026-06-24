@extends('layouts.app')

@section('content')
<div class="container py-5" style="background-color: #F8FAFC; font-family: 'Poppins', sans-serif; color: #1E293B;">
    <h2 class="fw-bold mb-4">Giỏ hàng của bạn</h2>



    
    @if($cartItems->isEmpty())
        <div class="text-center py-5 bg-white shadow-sm rounded border-0">
            <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
            <h4 class="text-muted mt-3">Giỏ hàng của bạn đang trống</h4>
            <p class="mb-4">Hãy quay lại trang danh sách để chọn mua sản phẩm nhé!</p>
            <a href="{{ route('products.index') }}" class="btn text-white px-4 py-2" style="background-color: #22C55E;">
                Tiếp tục mua sắm
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4">Sản Phẩm</th>
                                        <th scope="col">Giá</th>
                                        <th scope="col" class="text-center">Số Lượng</th>
                                        <th scope="col">Tổng Tiền</th>
                                        <th scope="col" class="text-center pe-4">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        @php($stock = optional($item->product->inventory)->quantity ?? 0)
                                        <tr data-cart-id="{{ $item->id }}" data-price="{{ $item->price }}">
                                            <td class="ps-4 fw-medium">
                                                {{ $item->product->name ?? 'N/A' }}
                                            </td>
                                            <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                            
                                            <td style="width: 180px;">
                                                <div class="d-flex flex-column align-items-center justify-content-center gap-1">
                                                    <div class="d-flex align-items-center">
                                                        <input type="number"
                                                           class="form-control form-control-sm text-center qty-input"
                                                           value="{{ $item->quantity }}"
                                                           min="1"
                                                           max="{{ $stock }}"
                                                           data-cart-id="{{ $item->id }}"
                                                           data-stock="{{ $stock }}"
                                                           data-price="{{ $item->price }}">
                                                    </div>
                                                    <span class="stock-warning text-danger small fw-bold text-center" style="font-size: 0.75rem; display: {{ $item->quantity > $stock ? 'block' : 'none' }};">
                                                        Vượt quá tồn kho (Tồn: {{ $stock }})
                                                    </span>
                                                    <span class="update-status small" style="font-size: 0.72rem;"></span>
                                                </div>
                                            </td>
                                            
                                            <td class="fw-bold row-total">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                                            
                                            <td class="text-center pe-4">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent" title="Xóa">
                                                        <i class="bi bi-trash-fill fs-5" style="color: #EF4444;"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Tóm tắt đơn hàng</h5>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tạm tính (<span id="total-qty">{{ $cartItems->sum('quantity') }}</span> sản phẩm)</span>
                            <span class="fw-medium" id="subtotal-display">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Tổng cộng:</span>
                            <span class="fw-bold fs-5" id="grand-total-display" style="color: #EF4444;">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
 
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout.index') }}" class="btn text-white fw-bold py-2" style="background-color: #22C55E;">
                                Tiến hành thanh toán
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary py-2">
                                Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Format số kiểu Việt Nam: dấu chấm ngăn hàng nghìn
    function formatVND(amount) {
        return amount.toLocaleString('vi-VN') + 'đ';
    }

    // Tính lại Tổng cộng từ tất cả các hàng
    function recalcGrandTotal() {
        let grandTotal = 0;
        let totalQty = 0;

        document.querySelectorAll('tr[data-cart-id]').forEach(row => {
            const price = parseFloat(row.dataset.price);
            const qtyInput = row.querySelector('.qty-input');
            const qty = parseInt(qtyInput.value) || 1;
            grandTotal += price * qty;
            totalQty += qty;
        });

        document.getElementById('subtotal-display').textContent   = formatVND(grandTotal);
        document.getElementById('grand-total-display').textContent = formatVND(grandTotal);
        document.getElementById('total-qty').textContent           = totalQty;
    }

    // Debounce để tránh gọi API liên tục khi user gõ nhanh
    function debounce(fn, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    async function updateCartItem(cartId, qty, input) {
        const row       = input.closest('tr');
        const stock     = parseInt(input.dataset.stock);
        const price     = parseFloat(input.dataset.price);
        const warning   = row.querySelector('.stock-warning');
        const statusEl  = row.querySelector('.update-status');
        const rowTotal  = row.querySelector('.row-total');

        // Validate phía client
        if (qty < 1) { input.value = 1; qty = 1; }
        if (qty > stock) {
            warning.style.display = 'block';
            warning.textContent   = `Vượt quá tồn kho (Tồn: ${stock})`;
            // Vẫn cho cập nhật UI tạm thời nhưng server sẽ từ chối
        } else {
            warning.style.display = 'none';
        }


        rowTotal.textContent = formatVND(price * qty);
        recalcGrandTotal();

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res   = await fetch(`/cart/update/${cartId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ quantity: qty }),
            });

            const data = await res.json();

            if (res.ok) {
                setTimeout(() => { statusEl.textContent = ''; }, 1500);
            } else {
                // Server từ chối (vd: vượt tồn kho)
                statusEl.style.color = '#EF4444';
                statusEl.textContent = data.message || 'Lỗi cập nhật';
                warning.style.display = 'block';
                warning.textContent   = data.message || `Vượt quá tồn kho (Tồn: ${stock})`;
            }
        } catch (err) {
            statusEl.style.color = '#EF4444';
            statusEl.textContent = 'Lỗi kết nối';
        }
    }

    const debouncedUpdate = debounce(updateCartItem, 600);

    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', function () {
            const cartId = this.dataset.cartId;
            let qty = parseInt(this.value);

            // Nếu chưa nhập xong (trường rỗng hoặc đang gõ) thì chờ
            if (this.value === '' || isNaN(qty)) return;

            // Ngay lập tức clamp về min=1 nếu user gõ số không hợp lệ
            if (qty < 1) {
                this.value = 1;
                qty = 1;
            }

            debouncedUpdate(cartId, qty, this);
        });

        // Khi user rời ô (blur/change): đảm bảo không để lại giá trị 0
        input.addEventListener('change', function () {
            const cartId = this.dataset.cartId;
            let qty = parseInt(this.value);

            if (isNaN(qty) || qty < 1) {
                this.value = 1;
                qty = 1;
            }

            updateCartItem(cartId, qty, this);
        });
    });
</script>
@endpush