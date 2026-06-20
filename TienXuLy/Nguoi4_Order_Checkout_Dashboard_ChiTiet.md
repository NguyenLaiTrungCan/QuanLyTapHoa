# NGƯỜI 4 - ĐƠN HÀNG (ORDER) + CHECKOUT + DASHBOARD

## 1. Tổng quan trách nhiệm

Người 4 chịu trách nhiệm toàn bộ quy trình xử lý đơn hàng từ lúc khách hàng bấm "Đặt hàng" cho đến khi đơn hàng hoàn thành hoặc bị hủy.

### Module phụ trách
- Quản lý đơn hàng (Order Management)
- Checkout (Thanh toán và tạo đơn hàng)
- Dashboard quản trị (Admin Dashboard)
- Thống kê doanh thu
- Theo dõi trạng thái đơn hàng
- Kiểm soát tính toàn vẹn dữ liệu đơn hàng

---

# 2. DATABASE

## Bảng orders

```sql
orders
(
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    total_price DECIMAL(10,2),
    status ENUM(
        'pending',
        'processing',
        'shipped',
        'delivered',
        'canceled'
    ),
    delivery_address TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Ý nghĩa
| Cột | Mô tả |
|------|--------|
| id | Mã đơn hàng |
| user_id | Khách hàng đặt |
| total_price | Tổng tiền |
| status | Trạng thái |
| delivery_address | Địa chỉ giao |
| created_at | Ngày tạo |
| updated_at | Ngày cập nhật |

---

## Bảng order_items

```sql
order_items
(
    id BIGINT PRIMARY KEY,
    order_id BIGINT,
    product_id BIGINT,
    quantity INT,
    price DECIMAL(10,2),
    created_at TIMESTAMP
);
```

### Ý nghĩa

Mỗi sản phẩm trong đơn hàng sẽ lưu thành một dòng trong bảng này.

Ví dụ:

Đơn #1001

- Coca Cola x2
- Mì Hảo Hảo x5

=> order_items có 2 record.

---

# 3. MODEL

## Order Model

```php
class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'delivery_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
```

---

## OrderItem Model

```php
class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
```

---

# 4. BACKEND - ORDER CONTROLLER

## index()

Hiển thị danh sách đơn hàng.

### Customer

Chỉ xem đơn của chính mình.

```php
Order::where('user_id', auth()->id());
```

### Admin

Xem toàn bộ đơn hàng.

```php
Order::all();
```

---

## show($id)

Xem chi tiết đơn hàng.

Hiển thị:

- Thông tin khách hàng
- Danh sách sản phẩm
- Số lượng
- Giá
- Tổng tiền
- Trạng thái

---

## updateStatus()

Chỉ Admin được sử dụng.

Workflow:

```text
pending
↓
processing
↓
shipped
↓
delivered
```

Không cho phép:

```text
delivered -> pending
```

---

## cancel()

Cho phép hủy đơn khi:

```text
status = pending
```

Sau khi hủy:

- đổi status thành canceled
- hoàn lại tồn kho

---

# 5. BACKEND - CHECKOUT CONTROLLER

## processCheckout()

Đây là chức năng quan trọng nhất của Người 4.

### Bước 1

Lấy toàn bộ sản phẩm trong giỏ hàng.

```php
$cartItems = Cart::where(
    'user_id',
    auth()->id()
)->get();
```

---

### Bước 2

Kiểm tra:

- giỏ hàng rỗng?
- còn đủ hàng?

---

### Bước 3

Tạo Order

```php
$order = Order::create([
    'user_id' => auth()->id(),
    'total_price' => $total,
    'status' => 'pending',
    'delivery_address' => $request->address
]);
```

---

### Bước 4

Tạo OrderItem

```php
foreach($cartItems as $item)
{
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $item->product_id,
        'quantity' => $item->quantity,
        'price' => $item->price
    ]);
}
```

---

### Bước 5

Trừ tồn kho

```php
$inventory->quantity -= $item->quantity;
```

---

### Bước 6

Xóa giỏ hàng

```php
Cart::where(
    'user_id',
    auth()->id()
)->delete();
```

---

### Bước 7

Trả về trang xác nhận đơn hàng.

---

# 6. TRANSACTION (BẮT BUỘC)

Toàn bộ checkout phải đặt trong transaction.

```php
DB::transaction(function(){

    // create order

    // create items

    // update inventory

    // clear cart

});
```

Nếu lỗi:

```text
ROLLBACK
```

Đảm bảo dữ liệu không bị sai.

---

# 7. API ROUTES

```php
Route::middleware('auth')->group(function(){

    Route::get('/orders',[OrderController::class,'index']);

    Route::get('/orders/{id}',[OrderController::class,'show']);

    Route::post('/orders/{id}/cancel',
        [OrderController::class,'cancel']
    );

    Route::post('/checkout',
        [CheckoutController::class,'processCheckout']
    );

});
```

Admin:

```php
Route::put(
    '/orders/{id}/status',
    [OrderController::class,'updateStatus']
);
```

---

# 8. FRONTEND - CHECKOUT

## checkout/index.blade.php

### Hiển thị

#### Danh sách sản phẩm

| Sản phẩm | SL | Giá | Thành tiền |
|-----------|----|------|------------|

---

#### Địa chỉ giao hàng

```html
<textarea name="address"></textarea>
```

---

#### Phương thức thanh toán

```html
COD

Banking
```

---

#### Tổng tiền

```text
Tạm tính

Phí ship

Tổng cộng
```

---

#### Nút đặt hàng

```html
<button>
Đặt hàng
</button>
```

---

# 9. FRONTEND - ORDER HISTORY

## orders/index.blade.php

### Customer

Hiển thị:

| Mã đơn | Ngày | Tổng tiền | Trạng thái |
|---------|------|-----------|-----------|

Nút:

```text
Xem chi tiết
```

---

### Admin

Thêm:

```text
Đổi trạng thái
```

---

# 10. FRONTEND - ORDER DETAIL

## orders/show.blade.php

### Thông tin

- Mã đơn
- Ngày đặt
- Khách hàng
- Địa chỉ

### Danh sách sản phẩm

| Tên | SL | Giá | Thành tiền |
|------|----|-----|-----------|

### Timeline

```text
Pending
↓
Processing
↓
Shipped
↓
Delivered
```

---

# 11. DASHBOARD ADMIN

## dashboard/index.blade.php

### Thẻ thống kê

- Tổng sản phẩm
- Tổng danh mục
- Tổng người dùng
- Tổng đơn hàng
- Doanh thu tháng
- Doanh thu tổng

---

## Biểu đồ doanh thu

30 ngày gần nhất.

Dữ liệu:

```php
Order::whereMonth(...)
```

---

## Biểu đồ trạng thái đơn hàng

- Pending
- Processing
- Shipped
- Delivered
- Canceled

---

## Top sản phẩm bán chạy

```sql
SUM(order_items.quantity)
GROUP BY product_id
```

---

## Đơn hàng gần đây

10 đơn mới nhất.

Hiển thị:

- Mã đơn
- Khách hàng
- Trạng thái
- Tổng tiền

---

# 12. SECURITY

## Bắt buộc

### Customer

Không được xem đơn người khác.

```php
if(
$order->user_id != auth()->id()
)
abort(403);
```

---

### Admin

Middleware:

```php
admin
```

Chỉ Admin được:

- xem tất cả đơn
- đổi trạng thái

---

# 13. TEST CASES

## Checkout

- Giỏ hàng rỗng
- Hết hàng
- Đặt thành công
- Đặt đồng thời nhiều người

---

## Orders

- Xem danh sách
- Xem chi tiết
- Hủy đơn

---

## Dashboard

- Thống kê đúng
- Biểu đồ đúng
- Doanh thu đúng

---

# 14. KẾT QUẢ BÀN GIAO

Người 4 hoàn thành khi có:

- Orders CRUD
- Checkout hoàn chỉnh
- Dashboard Admin
- Order History
- Order Detail
- Order Status Workflow
- Dashboard Statistics
- Authorization & Security
- Testing đầy đủ

## Ước tính khối lượng

- Backend: 55%
- Frontend: 35%
- Testing: 10%

Đây là module phức tạp nhất dự án vì kết nối trực tiếp với Cart, Inventory, User và Product.
