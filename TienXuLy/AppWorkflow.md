# AppWorkflow.md

# LUỒNG XỬ LÝ CHI TIẾT - HỆ THỐNG QUẢN LÝ TẠP HÓA (LARAVEL)

## 1. Kiến trúc tổng thể

```text
User/Admin
    ↓
Routes (web.php / api.php)
    ↓
Middleware (Auth, Admin)
    ↓
Controller
    ↓
Service / Business Logic
    ↓
Model (Eloquent ORM)
    ↓
MySQL Database
    ↓
Response (Blade View / JSON)
```

---

# 2. Luồng khách hàng (Customer Flow)

## 2.1 Truy cập hệ thống

```text
Khách truy cập
    ↓
Trang chủ
    ↓
Danh sách sản phẩm
    ↓
Xem chi tiết sản phẩm
```

Hệ thống:

1. ProductController@index
2. Lấy dữ liệu Product + Category + Inventory
3. Trả về View Product Listing

---

## 2.2 Đăng ký tài khoản

```text
Register Form
    ↓
Validate dữ liệu
    ↓
Tạo User
    ↓
Hash Password
    ↓
Lưu Database
    ↓
Đăng nhập tự động
```

Bảng liên quan:

- users

Controller:

- AuthController@register

---

## 2.3 Đăng nhập

```text
Login Form
    ↓
Kiểm tra Email
    ↓
Kiểm tra Password
    ↓
Tạo Session
    ↓
Chuyển về Trang chủ
```

Middleware:

- auth
- guest

---

## 2.4 Xem sản phẩm

```text
Product List
    ↓
Search
    ↓
Filter Category
    ↓
Sort Price
    ↓
Pagination
```

Controller:

- ProductController@index

Database:

- products
- categories
- inventories

---

## 2.5 Xem chi tiết sản phẩm

```text
Product Detail
    ↓
Hiển thị tồn kho
    ↓
Chọn số lượng
    ↓
Add To Cart
```

Controller:

- ProductController@show

---

## 2.6 Thêm vào giỏ hàng

```text
Click Add To Cart
    ↓
Kiểm tra đăng nhập
    ↓
Kiểm tra tồn kho
    ↓
Đã có trong giỏ?
   ├─ Có → Cập nhật số lượng
   └─ Không → Tạo Cart Item
    ↓
Lưu Database
```

Controller:

- CartController@add

Tables:

- carts
- inventories

---

## 2.7 Cập nhật giỏ hàng

```text
Cart Page
    ↓
Tăng/Giảm số lượng
    ↓
Kiểm tra tồn kho
    ↓
Tính lại subtotal
    ↓
Tính lại total
```

Controller:

- CartController@update

---

## 2.8 Checkout

```text
Cart
    ↓
Checkout Form
    ↓
Nhập địa chỉ giao hàng
    ↓
Chọn phương thức thanh toán
    ↓
Đặt hàng
```

Controller:

- CheckoutController@showCheckout

---

## 2.9 Xử lý Checkout (Quan trọng nhất)

```text
Nhấn Place Order
        ↓
Validate Cart
        ↓
Validate Stock
        ↓
BEGIN TRANSACTION
        ↓
Tạo Order
        ↓
Tạo OrderItems
        ↓
Giảm Inventory
        ↓
Xóa Cart
        ↓
COMMIT
        ↓
Order Confirmation
```

Chi tiết:

### Bước 1

Lấy toàn bộ cart của user.

### Bước 2

Kiểm tra:

- Cart không rỗng
- Sản phẩm tồn tại
- Stock đủ

### Bước 3

Tạo bản ghi Order

```text
orders
```

### Bước 4

Tạo OrderItems

```text
order_items
```

### Bước 5

Giảm số lượng kho

```text
inventories.quantity
```

### Bước 6

Xóa toàn bộ cart

```text
carts
```

### Bước 7

Trả về trang xác nhận đơn hàng

Controller:

- CheckoutController@processCheckout

---

## 2.10 Theo dõi đơn hàng

```text
My Orders
    ↓
Danh sách đơn hàng
    ↓
Chi tiết đơn hàng
    ↓
Trạng thái vận chuyển
```

Workflow:

```text
Pending
    ↓
Processing
    ↓
Shipped
    ↓
Delivered
```

Hoặc:

```text
Pending
    ↓
Canceled
```

---

# 3. Luồng Admin

## 3.1 Đăng nhập Admin

```text
Login
    ↓
Middleware Admin
    ↓
Dashboard
```

Kiểm tra:

```php
auth()->user()->isAdmin()
```

---

## 3.2 Dashboard

DashboardController@index

Tổng hợp:

```text
Products Count
Categories Count
Users Count
Orders Count
Revenue
Top Products
Low Stock Alerts
Recent Orders
```

Nguồn dữ liệu:

- products
- categories
- users
- orders
- inventories

---

## 3.3 Quản lý Danh mục

```text
Danh sách danh mục
      ↓
Create
Update
Delete
```

Controller:

- CategoryController

Table:

- categories

---

## 3.4 Quản lý Sản phẩm

```text
Danh sách sản phẩm
      ↓
Create
Update
Delete
```

Controller:

- ProductController

Tables:

- products
- categories

---

## 3.5 Quản lý Kho

```text
Inventory List
      ↓
Xem tồn kho
      ↓
Cập nhật tồn kho
```

Controller:

- InventoryController

Table:

- inventories

---

## 3.6 Quản lý Đơn hàng

```text
Orders List
      ↓
Xem chi tiết
      ↓
Cập nhật trạng thái
```

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

Controller:

- OrderController

Tables:

- orders
- order_items

---

# 4. Luồng Database

## Category → Product

```text
Category
   1
   │
   │
   N
Product
```

## Product → Inventory

```text
Product
   1
   │
   │
   1
Inventory
```

## User → Cart

```text
User
   1
   │
   │
   N
Cart
```

## User → Order

```text
User
   1
   │
   │
   N
Order
```

## Order → OrderItem

```text
Order
   1
   │
   │
   N
OrderItem
```

---

# 5. Luồng Middleware

## Guest Routes

```text
Register
Login
```

Middleware:

```text
guest
```

## Protected Routes

```text
Cart
Checkout
Orders
Profile
```

Middleware:

```text
auth
```

## Admin Routes

```text
Dashboard
Products CRUD
Categories CRUD
Inventory CRUD
Order Management
```

Middleware:

```text
auth
admin
```

---

# 6. Luồng API chính

## Product

```text
GET    /api/products
GET    /api/products/{id}
POST   /api/products
PUT    /api/products/{id}
DELETE /api/products/{id}
```

## Category

```text
GET    /api/categories
POST   /api/categories
PUT    /api/categories/{id}
DELETE /api/categories/{id}
```

## Cart

```text
GET    /api/cart
POST   /api/cart/add
PUT    /api/cart/{id}
DELETE /api/cart/{id}
```

## Order

```text
GET    /api/orders
GET    /api/orders/{id}
PUT    /api/orders/{id}/status
POST   /api/orders/{id}/cancel
```

## Checkout

```text
POST /api/checkout
```

---

# 7. Luồng End-to-End Toàn Hệ Thống

```text
Khách truy cập
      ↓
Đăng ký / Đăng nhập
      ↓
Xem sản phẩm
      ↓
Thêm vào giỏ hàng
      ↓
Kiểm tra tồn kho
      ↓
Checkout
      ↓
Tạo Order
      ↓
Giảm Inventory
      ↓
Xóa Cart
      ↓
Xác nhận đơn hàng
      ↓
Admin xử lý đơn
      ↓
Pending
      ↓
Processing
      ↓
Shipped
      ↓
Delivered
```

Đây là luồng nghiệp vụ hoàn chỉnh của toàn bộ hệ thống quản lý tạp hóa Laravel.
