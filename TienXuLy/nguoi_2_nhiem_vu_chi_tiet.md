# Nhiệm Vụ Chi Tiết - Người 2

## 1. Phạm vi công việc
Người 2 phụ trách 2 phần chính của dự án:

- Quản lý danh mục sản phẩm
- Xác thực người dùng (đăng ký, đăng nhập, hồ sơ cá nhân)

Mục tiêu là hoàn thiện đầy đủ cả frontend và backend cho 2 phần này, đồng thời đảm bảo giao diện phù hợp với file UI/UX của dự án.

---

## 1.1. Trạng thái hiện tại

### Đã hoàn thành

- CRUD danh mục ở backend và giao diện admin
- Đăng ký, đăng nhập, đăng xuất, hồ sơ cá nhân, đổi mật khẩu
- Seeder cho danh mục và tài khoản test
- Validation cho category, login, register, profile, password
- Navbar theo trạng thái đăng nhập, có dropdown tài khoản và biểu tượng giỏ hàng hiển thị theo layout
- Giao diện đồng bộ theo màu xanh lá - vàng của UIUX
- Rate limit cho đăng nhập và session lifetime ngắn hơn theo cấu hình
- Feature tests cho luồng auth và CRUD danh mục
- API Categories CRUD endpoints (GET/POST/PUT/DELETE)

### Còn mở rộng hoặc phụ thuộc nhóm

- API routes cho auth nếu nhóm thật sự dùng API riêng
- Kiểm thử tự động và Postman collection cho toàn bộ endpoint
- Tích hợp sâu với luồng sản phẩm, cart, orders của các thành viên khác

---

## 2. Kết quả cần bàn giao

- CRUD danh mục cho trang admin
- Trang đăng ký, đăng nhập, hồ sơ cá nhân
- Tích hợp lọc sản phẩm theo danh mục ở giao diện khách hàng
- Navbar hiển thị đúng trạng thái đăng nhập
- Validation, middleware, session và bảo mật cơ bản cho auth
- Dữ liệu mẫu cho danh mục và người dùng test

---

## 3. Nhiệm vụ chi tiết

### A. Database và Model

#### 1) Danh mục
- [ ] Tạo migration cho bảng `categories`
- [ ] Tạo model `Category`
- [ ] Thêm các cột cần thiết: `id`, `name`, `description`, `created_at`, `updated_at`
- [ ] Tạo seeder cho danh mục mẫu, khoảng 5-10 dữ liệu
- [ ] Khai báo quan hệ `Category hasMany Product`

#### 2) Người dùng
- [ ] Cập nhật migration bảng `users`
- [ ] Thêm cột `phone`
- [ ] Thêm cột `address`
- [ ] Tạo seeder cho user mẫu
- [ ] Chuẩn bị ít nhất 1 tài khoản admin và 1-2 tài khoản customer để test
- [ ] Khai báo quan hệ phù hợp với hệ thống:
  - [ ] `User hasMany Cart`
  - [ ] `User hasMany Order`

---

### B. Backend - Quản lý danh mục

- [ ] Tạo `CategoryController`
- [ ] Xây dựng các hàm:
  - [ ] `index()` - danh sách danh mục
  - [ ] `create()` - form thêm mới
  - [ ] `store()` - lưu danh mục
  - [ ] `edit()` - form chỉnh sửa
  - [ ] `update()` - cập nhật danh mục
  - [ ] `destroy()` - xóa danh mục
- [ ] Tạo validation request cho danh mục
- [ ] Kiểm tra tên danh mục không được trùng
- [ ] Cho phép mô tả danh mục là tùy chọn
- [ ] Thêm route cho category ở cả web và API nếu nhóm dùng API
- [ ] Bảo vệ chức năng admin bằng middleware phù hợp
- [ ] Test đầy đủ các thao tác CRUD

---

### C. Backend - Xác thực người dùng

- [ ] Tạo `AuthController`
- [ ] Xây dựng các chức năng:
  - [ ] `showRegister()` - hiển thị form đăng ký
  - [ ] `register()` - xử lý đăng ký
  - [ ] `showLogin()` - hiển thị form đăng nhập
  - [ ] `login()` - xử lý đăng nhập
  - [ ] `logout()` - đăng xuất
  - [ ] `profile()` - xem thông tin cá nhân
  - [ ] `updateProfile()` - cập nhật thông tin cá nhân
  - [ ] `changePassword()` - đổi mật khẩu
- [ ] Tạo request validation cho đăng ký
- [ ] Tạo request validation cho đăng nhập
- [ ] Bắt buộc kiểm tra các trường:
  - [ ] Họ tên
  - [ ] Email
  - [ ] Số điện thoại
  - [ ] Địa chỉ
  - [ ] Mật khẩu
  - [ ] Xác nhận mật khẩu
- [ ] Mã hóa mật khẩu bằng bcrypt
- [ ] Thiết lập session login/logout
- [ ] Áp dụng middleware `guest` cho trang login/register
- [ ] Áp dụng middleware `auth` cho profile và các trang cần đăng nhập
- [ ] Thêm route cho auth ở `web.php` và `api.php` nếu cần
- [ ] Test các luồng đăng ký, đăng nhập, đăng xuất, cập nhật hồ sơ, đổi mật khẩu

---

### D. Frontend - Danh mục

- [ ] Tạo trang danh sách danh mục cho admin
- [ ] Tạo view `categories/index.blade.php`
- [ ] Hiển thị các cột:
  - [ ] ID
  - [ ] Tên danh mục
  - [ ] Mô tả
  - [ ] Số lượng sản phẩm
  - [ ] Action
- [ ] Tạo view `categories/create.blade.php`
- [ ] Tạo view `categories/edit.blade.php`
- [ ] Thêm nút sửa và xóa danh mục
- [ ] Tạo hộp thoại xác nhận trước khi xóa
- [ ] Tạo category sidebar hoặc dropdown để lọc sản phẩm ở phía khách hàng
- [ ] Hiển thị số lượng sản phẩm theo danh mục nếu dữ liệu cho phép

---

### E. Frontend - Authentication

#### 1) Trang đăng ký
- [ ] Tạo view `auth/register.blade.php`
- [ ] Thêm các ô nhập:
  - [ ] Họ tên
  - [ ] Email
  - [ ] Số điện thoại
  - [ ] Địa chỉ
  - [ ] Mật khẩu
  - [ ] Xác nhận mật khẩu
- [ ] Hiển thị lỗi validation ngay trên form
- [ ] Có nút đăng ký rõ ràng
- [ ] Có link chuyển sang trang đăng nhập

#### 2) Trang đăng nhập
- [ ] Tạo view `auth/login.blade.php`
- [ ] Thêm các ô nhập:
  - [ ] Email
  - [ ] Mật khẩu
- [ ] Thêm checkbox `Remember me`
- [ ] Có nút đăng nhập
- [ ] Có link chuyển sang trang đăng ký
- [ ] Có thể thêm link quên mật khẩu nếu nhóm muốn mở rộng

#### 3) Trang hồ sơ cá nhân
- [ ] Tạo view `auth/profile.blade.php`
- [ ] Hiển thị thông tin người dùng đang đăng nhập
- [ ] Có nút chỉnh sửa hồ sơ
- [ ] Có thể hiển thị thêm số điện thoại và địa chỉ

#### 4) Trang chỉnh sửa hồ sơ
- [ ] Tạo view `auth/edit-profile.blade.php`
- [ ] Cho phép sửa thông tin cá nhân
- [ ] Có khu vực đổi mật khẩu
- [ ] Hiển thị thông báo thành công hoặc lỗi rõ ràng

#### 5) Navbar
- [ ] Cập nhật navbar để hiển thị đúng trạng thái đăng nhập
- [ ] Nếu chưa đăng nhập thì hiển thị nút Login/Register
- [ ] Nếu đã đăng nhập thì hiển thị tên người dùng
- [ ] Thêm menu Profile và Logout

---

## 4. Thứ tự triển khai đề xuất

### Giai đoạn 1: Chuẩn bị dữ liệu
- Migration categories
- Cập nhật users migration
- Model và seeder
- Relationships

### Giai đoạn 2: Backend
- CategoryController
- AuthController
- Validation request
- Route và middleware

### Giai đoạn 3: Frontend
- Trang danh mục admin
- Trang login/register
- Trang profile/edit profile
- Navbar theo trạng thái auth

### Giai đoạn 4: Kiểm thử
- Test CRUD danh mục
- Test đăng ký, đăng nhập, đăng xuất
- Test cập nhật hồ sơ
- Test validation và middleware

---

## 5. Checklist ngắn để nộp việc

- [ ] Hoàn thành CRUD danh mục
- [ ] Hoàn thành đăng ký và đăng nhập
- [ ] Hoàn thành hồ sơ cá nhân
- [ ] Hoàn thành navbar auth
- [ ] Có dữ liệu mẫu để demo
- [ ] Kiểm thử xong các luồng chính
- [ ] Đẩy code lên Git đúng quy ước nhóm

---

## 6. Ghi chú phối hợp với các thành viên khác

- Cần đồng bộ với người 1 để category lọc được sản phẩm
- Cần đồng bộ với người 3 để user có thể dùng cart và order sau khi đăng nhập
- Cần thống nhất tên route, tên biến và layout chung của giao diện
- Cần giữ style UI theo file UI/UX của dự án
