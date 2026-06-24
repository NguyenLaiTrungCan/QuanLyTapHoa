# 🎨 Thiết Kế Lại Admin Dashboard - Hệ Thống Quản Lý Tạp Hóa Laravel

## 1. Mục Tiêu Thiết Kế

* Giữ nguyên cấu trúc Dashboard hiện tại.
* Nâng cấp giao diện theo phong cách hiện đại tương tự:

  * Sapo
  * KiotViet
  * Shopify Admin
  * Shopee Seller Center
* Tăng khả năng theo dõi dữ liệu nhanh.
* Tối ưu trải nghiệm quản trị viên.

---

# 2. Bảng Màu

## Primary

```css
#2563EB
```

## Success

```css
#10B981
```

## Warning

```css
#F59E0B
```

## Danger

```css
#EF4444
```

## Background

```css
#F8FAFC
```

## Card Background

```css
#FFFFFF
```

---

# 3. Typography

Font đề xuất:

```html
Poppins
```

Hoặc:

```html
Inter
```

Font Weight:

| Thành phần        | Weight |
| ----------------- | ------ |
| Tiêu đề Dashboard | 700    |
| Tiêu đề Card      | 600    |
| Giá trị KPI       | 700    |
| Nội dung          | 400    |

---

# 4. Layout Tổng Thể

```text
Dashboard Header
────────────────────────────────────

[KPI 1] [KPI 2] [KPI 3] [KPI 4]

[Revenue Month] [Revenue Total]

[Revenue Chart]      [Order Status]

[Top Products]       [Status Detail]

[Recent Orders]
```

---

# 5. Dashboard Header

## Thiết kế

Bên trái:

```text
Dashboard

Xin chào Admin 👋

Theo dõi tình hình kinh doanh hôm nay
```

Bên phải:

```text
🔔 Thông báo
👤 Hồ sơ Admin
```

## Mô tả

Giúp dashboard thân thiện hơn thay vì chỉ hiển thị:

```text
Admin Dashboard
```

---

# 6. Khu Vực KPI Cards

Hiển thị 4 thẻ thống kê.

## Layout

```text
┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐
│ KPI1 │ │ KPI2 │ │ KPI3 │ │ KPI4 │
└──────┘ └──────┘ └──────┘ └──────┘
```

---

## Card 1 - Tổng Sản Phẩm

Icon:

```html
fa-box
```

Màu:

```css
bg-blue-50
```

Nội dung:

```text
📦 Tổng Sản Phẩm

120

+12 sản phẩm mới
```

---

## Card 2 - Tổng Danh Mục

Icon:

```html
fa-tags
```

Màu:

```css
bg-green-50
```

Nội dung:

```text
🏷 Tổng Danh Mục

5

+1 danh mục mới
```

---

## Card 3 - Tổng Người Dùng

Icon:

```html
fa-users
```

Màu:

```css
bg-purple-50
```

Nội dung:

```text
👥 Tổng Người Dùng

100

+15 người dùng mới
```

---

## Card 4 - Tổng Đơn Hàng

Icon:

```html
fa-cart-shopping
```

Màu:

```css
bg-orange-50
```

Nội dung:

```text
🛒 Tổng Đơn Hàng

356

+18 hôm nay
```

---

# 7. Khu Vực Doanh Thu

## Layout

```text
┌─────────────────────┐
│ Doanh Thu Tháng     │
└─────────────────────┘

┌─────────────────────┐
│ Tổng Doanh Thu      │
└─────────────────────┘
```

---

## Card Doanh Thu Tháng

Hiển thị:

```text
💰 Doanh Thu Tháng

12.500.000đ

↑ +15% so với tháng trước
```

Màu:

```css
text-success
```

---

## Card Tổng Doanh Thu

Hiển thị:

```text
💵 Tổng Doanh Thu

256.000.000đ

↑ +22% năm nay
```

Màu:

```css
text-primary
```

---

# 8. Biểu Đồ Doanh Thu

## Kích thước

Chiếm khoảng:

```text
70%
```

chiều ngang dashboard.

---

## Công nghệ

```javascript
Chart.js
```

---

## Kiểu Biểu Đồ

```text
Line Chart
```

Hiển thị:

* Doanh thu 30 ngày
* Doanh thu theo tuần
* Doanh thu theo tháng

---

## Bộ Lọc

```text
[7 ngày]
[30 ngày]
[90 ngày]
[1 năm]
```

---

# 9. Trạng Thái Đơn Hàng

## Kích thước

Chiếm:

```text
30%
```

bên phải biểu đồ doanh thu.

---

## Kiểu hiển thị

```text
Doughnut Chart
```

Hoặc:

```text
Pie Chart
```

---

## Dữ liệu

```text
Pending

Processing

Shipping

Delivered

Cancelled
```

---

# 10. Top Sản Phẩm Bán Chạy

## Thiết kế

Thay bảng đơn giản bằng danh sách trực quan.

---

## Hiển thị

```text
🥇 Coca Cola
█████████████ 150

🥈 Pepsi
███████████ 120

🥉 Mì Hảo Hảo
█████████ 95
```

---

## Thông tin

Mỗi sản phẩm gồm:

* Ảnh đại diện
* Tên sản phẩm
* Số lượng bán
* Thanh tiến độ

---

# 11. Chi Tiết Trạng Thái Đơn Hàng

Hiển thị dưới dạng Badge.

Ví dụ:

```html
<span class="badge bg-warning">
Pending (12)
</span>
```

```html
<span class="badge bg-primary">
Processing (5)
</span>
```

```html
<span class="badge bg-info">
Shipping (8)
</span>
```

```html
<span class="badge bg-success">
Delivered (45)
</span>
```

```html
<span class="badge bg-danger">
Cancelled (2)
</span>
```

---

# 12. Đơn Hàng Gần Đây

## Layout

| Mã Đơn | Khách Hàng | Tổng Tiền | Trạng Thái | Ngày Đặt |
| ------ | ---------- | --------- | ---------- | -------- |

---

## Ví Dụ

| Mã Đơn | Khách Hàng   | Tổng Tiền | Trạng Thái | Ngày Đặt   |
| ------ | ------------ | --------- | ---------- | ---------- |
| DH001  | Nguyễn Văn A | 350.000đ  | Delivered  | 20/06/2026 |
| DH002  | Trần Văn B   | 120.000đ  | Pending    | 20/06/2026 |
| DH003  | Lê Văn C     | 500.000đ  | Processing | 19/06/2026 |

---

## Badge Trạng Thái

### Pending

```css
bg-warning
```

### Processing

```css
bg-primary
```

### Shipping

```css
bg-info
```

### Delivered

```css
bg-success
```

### Cancelled

```css
bg-danger
```

---

# 13. Hiệu Ứng Card

```css
.dashboard-card{
    border:none;
    border-radius:16px;
    background:#fff;
    box-shadow:0 2px 12px rgba(0,0,0,.05);
    transition:.3s;
}
```

Hover:

```css
.dashboard-card:hover{
    transform:translateY(-4px);
}
```

---

# 14. Responsive

## Desktop

```text
4 KPI / hàng
```

---

## Tablet

```text
2 KPI / hàng
```

---

## Mobile

```text
1 KPI / hàng
```

---

# 15. Kết Quả Mong Muốn

Dashboard sau khi redesign sẽ:

* Hiện đại hơn.
* Chuyên nghiệp hơn.
* Dễ theo dõi doanh thu.
* Dễ quản lý đơn hàng.
* Phù hợp dự án Laravel quản lý tạp hóa.
* Giữ nguyên cấu trúc dữ liệu và logic hiện tại.
* Chỉ cần thay đổi HTML, CSS và Chart.js.
