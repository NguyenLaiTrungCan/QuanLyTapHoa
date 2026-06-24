<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản Lý Tạp Hóa')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @stack('styles')
    <style>
        :root {
            --brand-primary: #22c55e;
            --brand-secondary: #facc15;
            --brand-danger: #ef4444;
            --brand-bg: #f8fafc;
            --brand-text: #1e293b;
            --brand-muted: #64748b;
            --brand-card: #ffffff;
            --brand-border: #dbe4ee;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(34, 197, 94, 0.12), transparent 28%),
                radial-gradient(circle at top right, rgba(250, 204, 21, 0.18), transparent 24%),
                linear-gradient(180deg, #ffffff 0%, var(--brand-bg) 100%);
            color: var(--brand-text);
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        h1, h2, h3, .brand-heading {
            font-family: Poppins, Inter, sans-serif;
        }

        .page-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        .navbar {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 60%, #15803d 100%) !important;
        }

        .navbar .nav-link,
        .navbar .navbar-brand {
            color: #fff !important;
        }

        .hero-panel,
        .ui-card {
            background: var(--brand-card);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1.5rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .soft-card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1.5rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .page-title {
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--brand-text);
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .4rem .8rem;
            border-radius: 999px;
            background: rgba(34, 197, 94, 0.12);
            color: #166534;
            font-size: .85rem;
            font-weight: 700;
            letter-spacing: .01em;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
        }

        .nav-search {
            min-width: min(100%, 420px);
        }

        .nav-search .form-control {
            border: 0;
            box-shadow: none;
            border-radius: 999px;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .nav-search .btn {
            border-radius: 999px;
        }

        .nav-link {
            transition: opacity .2s ease, transform .2s ease;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link:focus {
            opacity: .9;
            transform: translateY(-1px);
        }

        .dropdown-user-menu {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.15);
        }

        .nav-icon-chip {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .45rem .75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
            text-decoration: none;
        }

        .hero-panel {
            overflow: hidden;
            position: relative;
        }

        .hero-panel::after {
            content: '';
            position: absolute;
            inset: auto -10% -35% auto;
            width: 260px;
            height: 260px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(250, 204, 21, 0.35), transparent 70%);
        }

        .soft-pill {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            border-radius: 999px;
            padding: .4rem .9rem;
            font-weight: 600;
            background: rgba(34, 197, 94, 0.12);
            color: #166534;
        }

        .section-title {
            font-size: clamp(1.4rem, 2vw, 2rem);
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .subtle {
            color: var(--brand-muted);
        }

        .footer-shell {
            border-top: 1px solid rgba(15, 23, 42, 0.08);
            background: rgba(255, 255, 255, 0.86);
            backdrop-filter: blur(10px);
        }

        .category-chip {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .85rem 1rem;
            border-radius: 1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: var(--brand-text);
            text-decoration: none;
        }

        .category-chip:hover {
            border-color: rgba(34, 197, 94, 0.35);
            background: rgba(34, 197, 94, 0.06);
        }

        .section-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1.25rem;
        }
    </style>
</head>
<body>
<div class="page-shell">
    <nav class="navbar navbar-expand-lg navbar-dark app-navbar">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
                <span class="brand-mark"><i class="bi bi-shop fs-5"></i></span>
                <span>Quản Lý Tạp Hóa</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-3 ms-lg-auto w-100">
                    <form class="nav-search d-flex ms-lg-auto" action="{{ route('products.index') }}" method="GET">
                        <input class="form-control form-control-sm" type="search" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm sản phẩm, danh mục...">
                        <button class="btn btn-warning btn-sm ms-2 fw-semibold" type="submit">Tìm</button>
                    </form>

                    @auth
                        <a class="nav-icon-chip" href="{{ Route::has('cart.index') ? route('cart.index') : '#' }}" @if (! Route::has('cart.index')) aria-disabled="true" tabindex="-1" @endif>
                            <i class="bi bi-cart3"></i>
                            @php($cartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->count() : 0)
                            <span id="cart-badge" class="badge text-bg-warning text-dark" style="{{ $cartCount === 0 ? 'display:none' : '' }}">{{ $cartCount ?: '' }}</span>
                        </a>

                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-user-menu dropdown-menu-end p-2">
                                @if (auth()->user()->isAdmin())
                                    <li><a class="dropdown-item rounded-3" href="{{ route('admin.dashboard.index') }}"><i class="bi bi-speedometer2 me-2"></i>Bảng Điều Khiển</a></li>
                                    <li><a class="dropdown-item rounded-3" href="{{ route('admin.products.index') }}"><i class="bi bi-box-seam me-2"></i>Sản Phẩm</a></li>
                                    <li><a class="dropdown-item rounded-3" href="{{ route('admin.categories.index') }}"><i class="bi bi-tags me-2"></i>Danh Mục</a></li>
                                    <li><a class="dropdown-item rounded-3" href="{{ route('admin.inventory.index') }}"><i class="bi bi-clipboard-data me-2"></i>Kho Hàng</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li><a class="dropdown-item rounded-3" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Hồ Sơ</a></li>
                                <li><a class="dropdown-item rounded-3" href="{{ route('profile.edit') }}"><i class="bi bi-pencil-square me-2"></i>Chỉnh Sửa Hồ Sơ</a></li>
                                @if (Route::has('orders.index'))
                                    <li><a class="dropdown-item rounded-3" href="{{ route('orders.index') }}"><i class="bi bi-receipt me-2"></i>Đơn Hàng</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item rounded-3 text-danger"><i class="bi bi-box-arrow-right me-2"></i>Đăng Xuất</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="d-flex gap-2 ms-lg-auto">
                            <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                            <a class="btn btn-warning btn-sm fw-semibold" href="{{ route('register') }}">Đăng ký</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if (session('success'))
                <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
                    <div class="toast align-items-center border-0 shadow-lg show" role="alert" aria-live="assertive" aria-atomic="true" style="background: linear-gradient(135deg, #ecfdf3 0%, #dcfce7 100%); color: #166534; border-left: 5px solid #16a34a;">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 fs-5 ms-2">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="toast-body fw-semibold">
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
                    <div class="toast align-items-center border-0 shadow-lg show" role="alert" aria-live="assertive" aria-atomic="true" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); color: #991b1b; border-left: 5px solid #dc2626;">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 fs-5 ms-2">
                                <i class="bi bi-exclamation-circle-fill"></i>
                            </div>
                            <div class="toast-body fw-semibold">
                                {{ session('error') }}
                            </div>
                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Vui lòng kiểm tra lại dữ liệu nhập vào.</strong>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer-shell py-4 mt-auto">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="fw-bold brand-heading">Quản Lý Tạp Hóa</div>

            </div>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.showAppToast = function (message, type = 'success') {
        const container = document.querySelector('.toast-container') || document.createElement('div');
        if (!container.classList.contains('toast-container')) {
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '1080';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = 'toast align-items-center border-0 shadow-lg show';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        const styles = {
            success: { background: 'linear-gradient(135deg, #ecfdf3 0%, #dcfce7 100%)', color: '#166534', borderLeft: '5px solid #16a34a' },
            danger: { background: 'linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%)', color: '#991b1b', borderLeft: '5px solid #dc2626' }
        };

        const style = styles[type] || styles.success;
        Object.assign(toast.style, {
            background: style.background,
            color: style.color,
            borderLeft: style.borderLeft,
            maxWidth: '320px'
        });

        const icon = type === 'danger' ? 'bi bi-exclamation-circle-fill' : 'bi bi-check-circle-fill';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 fs-5 ms-2"><i class="${icon}"></i></div>
                <div class="toast-body fw-semibold">${message}</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('show');
            toast.remove();
        }, 2800);
    };

    document.addEventListener('DOMContentLoaded', function () {

        // ── Hàm cập nhật badge giỏ hàng ──────────────────────────────────────
        function updateCartBadge(count) {
            const badge = document.getElementById('cart-badge');
            if (!badge) return;
            if (count > 0) {
                badge.textContent   = count;
                badge.style.display = '';
            } else {
                badge.textContent   = '';
                badge.style.display = 'none';
            }
        }

        // ── AJAX: Thêm vào giỏ hàng ──────────────────────────────────────────
        document.querySelectorAll('form.cart-add-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const btn       = form.querySelector('button[type="submit"]');
                const origText  = btn ? btn.textContent.trim() : '';
                const origClass = btn ? btn.className : '';

                if (btn) {
                    btn.disabled    = true;
                    btn.textContent = 'Đang thêm...';
                }

                const formData  = new FormData(form);
                const csrfToken = form.querySelector('input[name="_token"]')?.value || '';

                fetch(form.action, {
                    method:  form.method || 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept':           'application/json',
                        'X-CSRF-TOKEN':     csrfToken,
                    },
                    body: formData,
                })
                .then(async function (response) {
                    const data = await response.json().catch(function () { return {}; });

                    if (response.ok) {
                        // Cập nhật badge
                        if (typeof data.cart_count !== 'undefined') {
                            updateCartBadge(data.cart_count);
                        }

                        window.showAppToast(data.message || 'Thêm sản phẩm thành công.', 'success');

                        if (btn) {
                            // Chuyển nút sang "✓ Đã thêm"
                            btn.textContent = '✓ Đã thêm';
                            btn.className   = btn.className
                                .replace('btn-success', 'btn-outline-success')
                                .replace('btn-primary', 'btn-outline-primary');

                            setTimeout(function () {
                                btn.textContent = origText;
                                btn.className   = origClass;
                                btn.disabled    = false;
                            }, 1500);
                        }
                    } else {
                        window.showAppToast(data.message || 'Không thể thêm sản phẩm.', 'danger');

                        if (btn) {
                            btn.textContent = 'Lỗi!';
                            setTimeout(function () {
                                btn.textContent = origText;
                                btn.className   = origClass;
                                btn.disabled    = false;
                            }, 2000);
                        }
                    }
                })
                .catch(function () {
                    window.showAppToast('Không thể thêm sản phẩm. Vui lòng thử lại.', 'danger');
                    if (btn) {
                        btn.textContent = origText;
                        btn.className   = origClass;
                        btn.disabled    = false;
                    }
                });
            });
        });
    });
</script>
@stack('scripts')
</body>
</html>
