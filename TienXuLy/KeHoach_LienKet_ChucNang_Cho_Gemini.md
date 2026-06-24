# Ke hoach lien ket chuc nang cho Gemini

## Muc tieu

Lien ket cac module hien co thanh mot luong hoan chinh:

Khach hang vao trang chu -> xem san pham -> chi tiet san pham -> them gio hang -> cap nhat gio hang -> checkout -> tao don hang -> xem don hang.

Admin dang nhap -> dashboard -> quan ly danh muc/san pham/kho/don hang -> cap nhat trang thai don.

Uu tien sua cac loi route/view/database mismatch truoc, sau do moi toi UI va toi uu.

## Hien trang can luu y

- `ProductController` da co day du CRUD va tra ve `products.index`, `products.show`, `admin.products.*`, nhung `routes/web.php` chua khai bao route web cho `products.*` va `admin.products.*`.
- `resources/views/products/index.blade.php` va `show.blade.php` dang dung `route('products.index')`, `route('products.show')`, `route('cart.add')`, `route('admin.products.index')`, nhung cac route nay chua ton tai hoac chua duoc dat ten.
- `resources/views/layouts/app.blade.php` dung `route('home')` cho search, trong khi search san pham nen ve `products.index`.
- `CartController` dang dung `CartRequest`; khi update cart, request khong gui `product_id` nen validation hien tai se fail.
- `CheckoutController` tao order voi `phone` va `note`, nhung migration `orders` hien tai chua co 2 cot nay.
- View `orders.index` va `orders.show` chua ton tai, trong khi `OrderController` da return 2 view do.
- `DashboardController` thong ke status `cancelled`, nhung migration/model dung `canceled`.
- `InventoryController` co route goi `edit`, nhung controller khong co method `edit`; method `show` lai tra ve view edit.
- Web route admin category dang nam trong middleware `auth` nhung chua gan `admin`; customer dang nhap van co the vao CRUD danh muc.
- API route dang dung `auth:sanctum` nhung project chua thay setup token/login API ro rang. Uu tien lam web flow truoc, API co the chuan hoa sau.
- Nhieu file Blade bi loi encoding tieng Viet. Khong can sua toan bo ngay, nhung cac file duoc cham vao nen luu UTF-8 va sua text hien thi chinh.

## Nguyen tac cho Gemini khi code

1. Lam tung giai doan, sau moi giai doan chay `php artisan route:list` va test luong bang trinh duyet.
2. Khong doi ten bang/cot neu khong can; neu them cot thi tao migration moi, khong sua migration cu neu database da migrate.
3. Dung route name thay vi hard-code URL trong Blade.
4. Tach route customer va admin ro rang.
5. Moi thao tac checkout, huy don, tru kho/hoan kho phai nam trong transaction.
6. Khong cho customer xem/sua don hang cua user khac.
7. Khong de admin route chi co `auth`; phai co `auth` + `admin`.

## Gioi han pham vi quet file cho Gemini

De tranh Gemini quet qua rong va sua lan man, moi prompt chi duoc doc/sua nhung file lien quan truc tiep den giai doan dang lam.

### Nguyen tac quet file

1. Truoc khi code, chi quet danh sach file trong muc `File duoc quet` cua giai doan do.
2. Chi dung lenh tim kiem co gioi han path, vi du:
   - `rg "products.index" routes resources/views app/Http/Controllers`
   - `rg "cart.add|cart.index" routes resources/views app/Http/Controllers app/Http/Requests`
3. Khong quet cac thu muc:
   - `vendor/`
   - `node_modules/`
   - `storage/`
   - `bootstrap/cache/`
   - `.git/`
4. Khong sua file ngoai danh sach `File can sua` neu chua co ly do ro rang.
5. Neu phat hien can sua file ngoai danh sach, phai bao cao ly do truoc, roi moi sua.
6. Khong format lai toan bo project; chi format file vua sua neu can.

### File duoc quet theo giai doan

- Giai doan 1:
  - `routes/web.php`
  - `app/Http/Controllers/ProductController.php`
  - `app/Http/Controllers/CartController.php`
  - `app/Http/Controllers/InventoryController.php`
  - `app/Http/Middleware/AdminMiddleware.php`
  - `bootstrap/app.php`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/products/index.blade.php`
  - `resources/views/products/show.blade.php`
  - `resources/views/cart/index.blade.php`
  - `resources/views/admin/products/*.blade.php`
  - `resources/views/categories/*.blade.php`
  - `resources/views/inventory/*.blade.php`

- Giai doan 2:
  - `app/Http/Requests/CartRequest.php`
  - `app/Http/Controllers/CartController.php`
  - `app/Models/Cart.php`
  - `app/Models/Product.php`
  - `app/Models/Inventory.php`
  - `resources/views/products/index.blade.php`
  - `resources/views/products/show.blade.php`
  - `resources/views/cart/index.blade.php`

- Giai doan 3:
  - `database/migrations/*create_orders_table.php`
  - `database/migrations/*create_order_items_table.php`
  - `database/migrations/*create_carts_table.php`
  - `app/Models/Order.php`
  - `app/Models/OrderItem.php`
  - `app/Models/Cart.php`
  - `app/Models/Inventory.php`
  - `app/Http/Requests/CheckoutRequest.php`
  - `app/Http/Controllers/CheckoutController.php`
  - `resources/views/checkout/index.blade.php`

- Giai doan 4:
  - `routes/web.php`
  - `app/Http/Controllers/OrderController.php`
  - `app/Models/Order.php`
  - `app/Models/OrderItem.php`
  - `app/Models/Inventory.php`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/orders/*.blade.php`

- Giai doan 5:
  - `routes/web.php`
  - `app/Http/Controllers/DashboardController.php`
  - `app/Http/Controllers/OrderController.php`
  - `app/Http/Controllers/InventoryController.php`
  - `app/Models/Order.php`
  - `app/Models/Inventory.php`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/admin/dashboard/index.blade.php`
  - `resources/views/admin/orders/*.blade.php`
  - `resources/views/inventory/*.blade.php`

- Giai doan 6:
  - `routes/web.php`
  - `app/Http/Controllers/ProductController.php`
  - `app/Models/Category.php`
  - `app/Models/Product.php`
  - `app/Models/Inventory.php`
  - `app/Models/OrderItem.php`
  - `resources/views/welcome.blade.php`
  - `resources/views/layouts/app.blade.php`

- Giai doan 7:
  - `routes/api.php`
  - `app/Http/Controllers/ProductController.php`
  - `app/Http/Controllers/CartController.php`
  - `app/Http/Controllers/CheckoutController.php`
  - `app/Http/Controllers/OrderController.php`
  - `app/Http/Controllers/InventoryController.php`
  - `app/Http/Controllers/Api/CategoryApiController.php`
  - `config/sanctum.php` neu ton tai
  - `composer.json`

- Giai doan 8:
  - Chi quet file bi loi theo output cua lenh test/build.
  - Neu loi route: quet `routes/` va view/controller co ten route do.
  - Neu loi migration/database: quet `database/migrations/`, model lien quan, controller tao/sua ban ghi.
  - Neu loi Blade: quet dung file Blade duoc stack trace chi ra.

## Giai doan 1: Sua route de cac module nhin thay nhau

### File can sua

- `routes/web.php`
- `app/Http/Controllers/InventoryController.php`
- `resources/views/layouts/app.blade.php`
- `resources/views/products/index.blade.php`
- `resources/views/products/show.blade.php`
- `resources/views/cart/index.blade.php`

### Viec can lam

1. Them import `ProductController` vao `routes/web.php`.
2. Doi route trang chu:
   - Co the de `/` la welcome.
   - Them link tu welcome/nav sang `/products`.
3. Them route customer cho san pham:
   - `GET /products` -> `ProductController@index` -> name `products.index`
   - `GET /products/{product}` -> `ProductController@show` -> name `products.show`
4. Doi route cart trong web thanh co name:
   - `GET /cart` -> name `cart.index`
   - `POST /cart/add` -> name `cart.add`
   - `PUT /cart/update/{id}` -> name `cart.update`
   - `DELETE /cart/remove/{id}` -> name `cart.remove`
   - `DELETE /cart/clear` -> name `cart.clear`
5. Them admin product routes:
   - prefix `/admin/products`
   - name prefix `admin.products.`
   - middleware `auth`, `admin`
   - map CRUD cua `ProductController`
6. Chuyen admin categories vao middleware `auth`, `admin`.
7. Chuan hoa inventory route:
   - prefix `/admin/inventory`
   - name prefix `admin.inventory.`
   - middleware `auth`, `admin`
   - `GET /` -> `InventoryController@index` -> `admin.inventory.index`
   - `GET /{inventory}/edit` -> `InventoryController@edit` -> `admin.inventory.edit`
   - `PUT /{inventory}` -> `InventoryController@update` -> `admin.inventory.update`
8. Sua `InventoryController`:
   - Them method `edit($id)` hoac doi route edit goi method hien co.
   - Neu giu `show`, chi dung cho API JSON; web edit nen co method `edit`.
9. Trong Blade:
   - Navbar search action chuyen sang `route('products.index')`, input name dung `search` thay vi `q`.
   - Cart link dung `route('cart.index')`.
   - Product add form phai gui hidden `product_id`.
   - `cart.index` dung `route('cart.update', $item)`, `route('cart.remove', $item)`, `route('checkout.index')`, `route('products.index')`.

### Nghiem thu

- `php artisan route:list` phai co:
  - `products.index`, `products.show`
  - `cart.index`, `cart.add`, `cart.update`, `cart.remove`, `cart.clear`
  - `admin.products.index/create/store/edit/update/destroy`
  - `admin.inventory.index/edit/update`
- Mo `/products` khong loi route missing.
- Bam "Chi tiet" sang duoc trang chi tiet.
- Bam "Them vao gio" khi da dang nhap tao/cap nhat cart.

## Giai doan 2: Sua gio hang va validation

### File can sua

- `app/Http/Requests/CartRequest.php`
- `app/Http/Controllers/CartController.php`
- `resources/views/products/index.blade.php`
- `resources/views/products/show.blade.php`
- `resources/views/cart/index.blade.php`

### Viec can lam

1. Sua `CartRequest` de validation theo route/action:
   - Khi `cart.add`: `product_id` required, `quantity` required.
   - Khi `cart.update`: `product_id` khong required, chi can `quantity`.
   - Co the dung `$this->routeIs('cart.add')` hoac kiem method/action.
2. Trong product list/detail:
   - Form add cart gui:
     - `_token`
     - `product_id`
     - `quantity`, mac dinh 1 o list, theo input o detail.
3. Trong `CartController`:
   - Neu request web thi redirect/back voi flash.
   - Neu request API/JSON thi tra JSON.
   - Khi loi het hang trong web khong nen tra JSON thuan; dung `back()->with('error', ...)`.
   - `index()` nen load `product.inventory` de hien thi ton kho va gioi han input quantity.
4. Trong `cart.index`:
   - Input quantity co `max` bang ton kho.
   - Hien thi canh bao neu san pham trong gio vuot ton kho hien tai.

### Nghiem thu

- Them moi san pham vao gio thanh cong.
- Them cung san pham lan 2 tang quantity.
- Update quantity trong cart khong bi loi `product_id required`.
- Neu quantity vuot stock, quay lai view va hien flash error.

## Giai doan 3: Sua checkout va cau truc order

### File can sua

- Tao migration moi trong `database/migrations`
- `app/Models/Order.php`
- `app/Http/Requests/CheckoutRequest.php`
- `app/Http/Controllers/CheckoutController.php`
- `resources/views/checkout/index.blade.php`

### Viec can lam

1. Tao migration moi them cot vao `orders`:
   - `phone` nullable hoac required tuy request hien tai, nen `string('phone')->nullable()`
   - `note` nullable text
   - Neu muon luu payment: them `payment_method` enum/string nullable/default `COD`
2. Cap nhat `Order::$fillable`:
   - da co `phone`, `note`; them `payment_method` neu tao cot.
3. Cap nhat `CheckoutRequest`:
   - Them rule `payment_method` neu view co gui: `required|in:COD,BANKING`
   - Phone nen co `max:20`.
4. Trong `CheckoutController@store`:
   - Load cart voi `product.inventory`.
   - Check cart rong.
   - Check product con ton tai.
   - Check stock.
   - Dung `DB::transaction(function () { ... })`.
   - Khi tru kho nen lock row de tranh checkout dong thoi:
     - `Inventory::where('product_id', ...)->lockForUpdate()->first()`
   - Tao `Order`.
   - Tao `OrderItem`.
   - Tru inventory.
   - Xoa cart.
5. Sau checkout redirect `orders.show`.

### Nghiem thu

- Chay migration thanh cong.
- Checkout tu gio hang tao 1 order, tao order_items, tru inventory, xoa cart.
- Neu 2 nguoi cung mua vuot ton kho, khong duoc tru am.
- Sau checkout vao trang chi tiet don hang.

## Giai doan 4: Tao man hinh don hang cho customer va admin

### File can tao

- `resources/views/orders/index.blade.php`
- `resources/views/orders/show.blade.php`

### File can sua

- `app/Http/Controllers/OrderController.php`
- `routes/web.php`
- `resources/views/layouts/app.blade.php`

### Viec can lam

1. Tao `orders.index`:
   - Customer: hien danh sach don cua chinh ho.
   - Admin: co the hien tat ca don, hoac tao route admin rieng o giai doan 5.
   - Cot can co: ma don, ngay tao, tong tien, trang thai, so san pham, nut xem chi tiet.
   - Neu status `pending`, hien nut huy don cho customer.
2. Tao `orders.show`:
   - Thong tin nguoi dat, phone, dia chi, note, status.
   - Bang item: san pham, so luong, don gia, thanh tien.
   - Tong tien.
   - Nut huy neu `pending`.
   - Neu admin, hien form cap nhat status.
3. Sua `OrderController@index`:
   - Nen paginate thay vi `get()`.
   - Load `orderItems` de dem item.
4. Sua `OrderController@show`:
   - Giu check ownership.
5. Sua `OrderController@cancel`:
   - Dam bao `orderItems` duoc load truoc khi goi `cancel()`.
   - Huy don phai hoan kho trong transaction.

### Nghiem thu

- Customer vao `/orders` chi thay don cua minh.
- Customer khong xem duoc `/orders/{id}` cua user khac.
- Customer huy don pending thi status thanh `canceled` va stock duoc cong lai.
- Don da `processing/shipped/delivered/canceled` khong huy duoc.

## Giai doan 5: Hoan thien admin flow

### File can sua/tao

- `routes/web.php`
- `app/Http/Controllers/DashboardController.php`
- `resources/views/admin/dashboard/index.blade.php`
- Co the tao:
  - `resources/views/admin/orders/index.blade.php`
  - `resources/views/admin/orders/show.blade.php`

### Viec can lam

1. Gom admin route vao group:
   - prefix `admin`
   - name `admin.`
   - middleware `auth`, `admin`
2. Dashboard:
   - Sua status `cancelled` -> `canceled`.
   - Revenue nen tinh don `delivered` hoac it nhat loai `canceled`; chon 1 cach va ghi ro trong code.
   - Them low stock alerts: `Inventory::with('product')->where('quantity', '<=', 5)->get()`.
3. Admin orders:
   - Route de quan ly don co the dung:
     - `GET /admin/orders` -> name `admin.orders.index`
     - `GET /admin/orders/{order}` -> name `admin.orders.show`
     - `PUT /admin/orders/{order}/status` -> name `admin.orders.updateStatus`
   - Neu dung chung `OrderController`, tach view theo admin/customer bang route name hoac method rieng.
4. Status workflow:
   - Cho phep:
     - `pending -> processing`
     - `processing -> shipped`
     - `shipped -> delivered`
     - `pending -> canceled`
   - Khong cho nhay lui.
   - Khong cho sua neu da `delivered` hoac `canceled`.
5. Navbar/admin dashboard:
   - Neu user la admin, them link Dashboard, Products, Categories, Inventory, Orders.

### Nghiem thu

- Customer dang nhap khong vao duoc `/admin/*`.
- Admin vao dashboard thay thong ke dung.
- Admin cap nhat trang thai don theo dung workflow.
- Dashboard khong con query status `cancelled`.

## Giai doan 6: Noi trang chu voi du lieu that

### File can sua

- `routes/web.php`
- Co the tao `HomeController` hoac dung closure gon trong route.
- `resources/views/welcome.blade.php`

### Viec can lam

1. Trang chu lay du lieu:
   - Categories moi/pho bien.
   - San pham moi.
   - San pham con hang.
   - San pham ban chay dua tren `order_items`.
2. Doi cac link demo dang tro ve login thanh:
   - Category chip -> `products.index?category={id}`
   - Product card -> `products.show`
   - Add cart -> `cart.add` neu auth; neu guest thi link login hoac form redirect login.
3. Navbar search chay tren `/products?search=...`.

### Nghiem thu

- Trang chu khong con san pham demo hard-code.
- Bam danh muc loc duoc san pham.
- Bam san pham vao chi tiet.

## Giai doan 7: Chuan hoa API sau khi web flow da on

### File can sua

- `routes/api.php`
- Cac controller dung chung web/API: `ProductController`, `CartController`, `CheckoutController`, `OrderController`, `InventoryController`

### Viec can lam

1. Quyet dinh API auth:
   - Neu dung Sanctum, cai va cau hinh Sanctum day du.
   - Neu chua can API login, tam de public read-only API va khong uu tien cart/order API.
2. Controller nao dung chung web/API can tach response:
   - `if ($request->expectsJson() || $request->is('api/*')) return response()->json(...)`
   - Web thi return view/redirect.
3. Inventory API khong duoc return Blade view.
4. Product API resource route can canh quyen:
   - Public: index/show.
   - Admin: store/update/destroy.

### Nghiem thu

- Goi API khong tra HTML ngoai y muon.
- API mutating route co auth/admin ro rang.

## Giai doan 8: Kiem thu tong the

### Lenh can chay

```bash
php artisan route:list
php artisan migrate
php artisan db:seed
php artisan test
```

Neu co dung frontend asset:

```bash
npm run build
```

### Test thu cong bat buoc

1. Guest:
   - Vao trang chu.
   - Vao danh sach san pham.
   - Search/filter/sort san pham.
   - Bam them gio hang thi duoc yeu cau login hoac redirect login hop ly.
2. Customer:
   - Dang ky/dang nhap.
   - Xem san pham.
   - Them gio hang.
   - Update/xoa gio hang.
   - Checkout.
   - Xem don hang.
   - Huy don pending.
3. Admin:
   - Dang nhap admin.
   - Vao dashboard.
   - CRUD danh muc.
   - CRUD san pham.
   - Cap nhat ton kho.
   - Xem tat ca don hang.
   - Cap nhat status don hang.
4. Bao mat:
   - Customer khong vao `/admin/*`.
   - Customer khong xem don user khac.
   - Customer khong goi duoc route update status.
5. Du lieu:
   - Checkout tru dung inventory.
   - Huy don hoan inventory.
   - Don canceled khong tinh doanh thu neu da chon quy tac loai canceled.

## Thu tu giao viec de Gemini code

Nen dua cho Gemini tung prompt theo thu tu sau:

### Prompt 1

Sua `routes/web.php` de khai bao day du route name cho products, cart, checkout, orders, admin products, admin categories, admin inventory. Dam bao admin route co middleware `auth` va `admin`. Sau khi sua, chay `php artisan route:list` va bao cao cac route name quan trong.

### Prompt 2

Sua cac Blade lien quan de khong con route missing: `layouts/app.blade.php`, `products/index.blade.php`, `products/show.blade.php`, `cart/index.blade.php`. Add cart form phai gui `product_id` va `quantity`. Dung route name, khong hard-code URL.

### Prompt 3

Sua `CartRequest` va `CartController` de add/update cart hoat dong cho web. Update cart khong bat buoc `product_id`. Loi het hang phai redirect back voi flash error tren web, JSON chi dung cho API.

### Prompt 4

Them migration cho `orders.phone`, `orders.note`, va neu can `orders.payment_method`. Sua `CheckoutRequest`, `CheckoutController`, `Order` model de checkout tao order dung, tru kho trong transaction co `lockForUpdate`, tao order items va xoa cart.

### Prompt 5

Tao `resources/views/orders/index.blade.php` va `resources/views/orders/show.blade.php`. Customer xem lich su don va chi tiet don. Admin co form cap nhat status trong trang chi tiet. Dam bao check quyen trong controller van dung.

### Prompt 6

Sua admin dashboard va inventory: status `canceled`, low stock alerts, route/method edit inventory, link admin trong navbar cho admin. Kiem tra customer khong vao duoc admin route.

### Prompt 7

Noi trang chu `welcome.blade.php` voi du lieu that: categories, san pham moi, san pham ban chay/con hang. Thay toan bo link demo bang route san pham/cart that.

### Prompt 8

Chay kiem thu: `php artisan route:list`, `php artisan migrate`, `php artisan db:seed`, `php artisan test`, `npm run build` neu co. Sua loi phat sinh cho den khi luong guest/customer/admin chay duoc.

## Dinh nghia hoan thanh

Du an duoc xem la da lien ket chuc nang khi:

- Khong con loi route missing trong cac trang chinh.
- Guest/customer/admin co navigation ro rang.
- Gio hang -> checkout -> don hang -> tru kho chay lien tuc.
- Admin xem va xu ly don hang duoc.
- Category/product/inventory/dashboard co link qua lai.
- Cac thao tac quan trong co validation va middleware dung.
- Kiem thu thu cong 3 vai tro guest/customer/admin dat yeu cau.
