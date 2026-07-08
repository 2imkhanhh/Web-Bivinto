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
          <Link class="nav-link" :class="{ 'active': $page.url.startsWith('/admin/categories') }"
            href="/admin/categories">
            <i class="fa-solid fa-list"></i> Danh mục
          </Link>
        </li>
        <li class="nav-item">
          <Link class="nav-link" :class="{ 'active': $page.url.startsWith('/admin/orders') }" href="/admin/orders">
            <i class="fa-solid fa-cart-shopping"></i> Đơn hàng
          </Link>
        </li>
        <li class="nav-item">
          <Link class="nav-link" :class="{ 'active': $page.url.startsWith('/admin/inventory') }"
            href="/admin/inventory">
            <i class="fa-solid fa-warehouse"></i> Kho hàng
          </Link>
        </li>
        <li class="nav-item">
          <Link class="nav-link" :class="{ 'active': $page.url.startsWith('/admin/customers') }"
            href="/admin/customers">
            <i class="fa-solid fa-users"></i> Khách hàng
          </Link>
        </li>
        <li class="nav-item">
          <Link class="nav-link" :class="{ 'active': $page.url.startsWith('/admin/blogs') }" href="/admin/blogs">
            <i class="fa-regular fa-newspaper"></i> Bài viết
          </Link>
        </li>
        <li class="nav-item">
          <Link class="nav-link" :class="{ 'active': $page.url.startsWith('/admin/settings') }" href="/admin/settings">
            <i class="fa-solid fa-pen-to-square"></i> Nội dung
          </Link>
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
        <div class="topbar-user admin-user-menu">
          <div class="admin-avatar-btn" @click="toggleUserMenu" id="adminUserBtn">
            <img src="https://ui-avatars.com/api/?name=Admin&background=1a1a2e&color=fff" alt="Admin" width="36"
              height="36" class="rounded-circle">
            <span class="admin-name">Quản trị viên</span>
            <i class="fa-solid fa-chevron-down admin-chevron" :class="{ 'rotated': userMenuOpen }"></i>
          </div>
          <div class="admin-user-dropdown" v-show="userMenuOpen" @click.stop>
            <div class="admin-dropdown-divider"></div>
            <a href="#" class="admin-dropdown-item">
              <i class="fa-regular fa-user"></i>
              <span>Hồ sơ</span>
            </a>
            <div class="admin-dropdown-divider"></div>
            <a href="#" class="admin-dropdown-item admin-dropdown-logout" @click.prevent="logout">
              <i class="fa-solid fa-arrow-right-from-bracket"></i>
              <span>Đăng xuất</span>
            </a>
          </div>
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
import { onMounted, ref } from 'vue';

defineProps({
  title: {
    type: String,
    default: 'Dashboard'
  }
});

const userMenuOpen = ref(false);

const toggleUserMenu = () => {
  userMenuOpen.value = !userMenuOpen.value;
};

// Close menu when clicking outside
if (typeof window !== 'undefined') {
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.admin-user-menu')) {
      userMenuOpen.value = false;
    }
  });
}

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
  window.showToast = function (message, type = 'success') {
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

<style scoped>
.admin-user-menu {
  position: relative;
}

.admin-avatar-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  padding: 6px 12px;
  border-radius: 50px;
  transition: background 0.2s;
  user-select: none;
}

.admin-avatar-btn:hover {
  background: #f1f3f5;
}

.admin-name {
  font-weight: 600;
  font-size: 0.9rem;
  color: #212529;
}

.admin-chevron {
  font-size: 0.7rem;
  color: #6c757d;
  transition: transform 0.25s ease;
}

.admin-chevron.rotated {
  transform: rotate(180deg);
}

.admin-user-dropdown {
  position: absolute;
  top: calc(100% + 10px);
  right: 0;
  width: 210px;
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.13);
  border: 1px solid #f0f0f0;
  z-index: 1050;
  overflow: hidden;
  animation: dropdownFadeIn 0.18s ease;
}

@keyframes dropdownFadeIn {
  from {
    opacity: 0;
    transform: translateY(-6px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.admin-dropdown-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 18px 16px 14px;
  background: #f8f9fa;
}

.admin-dropdown-name {
  font-weight: 700;
  font-size: 0.95rem;
  color: #212529;
  margin-top: 4px;
}

.admin-dropdown-role {
  font-size: 0.78rem;
  color: #6c757d;
  background: #e9ecef;
  padding: 2px 10px;
  border-radius: 20px;
  margin-top: 3px;
}

.admin-dropdown-divider {
  height: 1px;
  background: #f0f0f0;
  margin: 0;
}

.admin-dropdown-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 18px;
  color: #212529;
  text-decoration: none;
  font-size: 0.88rem;
  font-weight: 500;
  transition: background 0.15s;
}

.admin-dropdown-item:hover {
  background: #f8f9fa;
  color: #0d6efd;
}

.admin-dropdown-item i {
  width: 16px;
  text-align: center;
  color: #6c757d;
}

.admin-dropdown-item:hover i {
  color: #0d6efd;
}

.admin-dropdown-logout {
  color: #dc3545;
}

.admin-dropdown-logout:hover {
  background: #fff5f5;
  color: #dc3545;
}

.admin-dropdown-logout:hover i {
  color: #dc3545;
}
</style>
