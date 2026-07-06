<template>
  <div>
    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column">
      <div class="sidebar-header">
        <h4>BIVINTO ADMIN</h4>
      </div>
      <ul class="nav flex-column mt-3">
        <li class="nav-item">
          <Link class="nav-link" :class="{ 'active': $page.url === '/admin' }" href="/admin">
            <i class="fa-solid fa-gauge"></i> Tổng quan
          </Link>
        </li>
        <li class="nav-item">
          <Link class="nav-link" :class="{ 'active': $page.url.startsWith('/admin/products') }" href="/admin/products">
            <i class="fa-solid fa-box-open"></i> Sản phẩm
          </Link>
        </li>
        <li class="nav-item">
          <Link class="nav-link" :class="{ 'active': $page.url.startsWith('/admin/categories') }" href="/admin/categories">
            <i class="fa-solid fa-list"></i> Danh mục
          </Link>
        </li>
        <li class="nav-item">
          <Link class="nav-link" :class="{ 'active': $page.url.startsWith('/admin/orders') }" href="/admin/orders">
            <i class="fa-solid fa-cart-shopping"></i> Đơn hàng
          </Link>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <i class="fa-solid fa-users"></i> Khách hàng
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <i class="fa-regular fa-newspaper"></i> Bài viết (Blogs)
          </a>
        </li>
      </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Topbar -->
      <header class="topbar">
        <div class="topbar-title">
          <h5 class="m-0 text-dark font-weight-bold">{{ title }}</h5>
        </div>
        <div class="topbar-user dropdown">
          <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
            id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin" width="32"
              height="32" class="rounded-circle me-2">
            <strong>Quản trị viên</strong>
          </a>
          <ul class="dropdown-menu dropdown-menu-end text-small shadow-sm" aria-labelledby="dropdownUser">
            <li><a class="dropdown-item" href="#">Cài đặt</a></li>
            <li><a class="dropdown-item" href="#">Hồ sơ</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#" @click.prevent="logout">Đăng xuất</a></li>
          </ul>
        </div>
      </header>

      <!-- Page Content -->
      <main class="content-area">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

defineProps({
    title: {
        type: String,
        default: 'Dashboard'
    }
});

const logout = () => {
    router.post('/logout', {}, {
        onFinish: () => {
            // Dọn dẹp local storage cũ nếu còn sót lại
            localStorage.removeItem('user');
            localStorage.removeItem('access_token');
            localStorage.removeItem('refresh_token');
        }
    });
};

// Global Toast logic
onMounted(() => {
    window.showToast = function(message, type = 'success') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        return Toast.fire({
            icon: type,
            title: message
        });
    };
});
</script>
