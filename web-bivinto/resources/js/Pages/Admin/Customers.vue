<template>
  <Layout title="Quản lý Khách hàng">
    <Head title="Khách hàng" />

    <!-- Success/Error Messages -->
    <div v-if="$page.props.flash && $page.props.flash.success" class="alert alert-success alert-dismissible fade show mb-3"
      role="alert">
      {{ $page.props.flash.success }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div v-if="Object.keys($page.props.errors).length > 0" class="alert alert-danger alert-dismissible fade show mb-3"
      role="alert">
      <ul class="mb-0">
        <li v-for="(error, key) in $page.props.errors" :key="key">{{ error }}</li>
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-dark">Danh sách Khách hàng</h5>
        <div>
          <input type="text" class="form-control d-inline-block form-control-sm" style="width: 300px;" placeholder="Tìm theo tên, sđt, email..."
            v-model="searchQuery" @input="debouncedSearch">
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th class="ps-4" style="width: 5%;">STT</th>
                <th>Khách hàng</th>
                <th>Số điện thoại</th>
                <th>Ngày tham gia</th>
                <th>Vai trò</th>
                <th class="text-end pe-4">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(customer, index) in customers.data" :key="customer.id">
                <td class="ps-4 text-muted">{{ (customers.current_page - 1) * customers.per_page + index + 1 }}</td>
                <td>
                  <div>{{ customer.name }}</div>
                  <div class="text-muted small">{{ customer.email }}</div>
                </td>
                <td>{{ customer.phone }}</td>
                <td>{{ formatDate(customer.created_at) }}</td>
                <td>
                  <span class="badge" :class="customer.role === 'admin' ? 'bg-primary' : 'bg-secondary'">
                    {{ customer.role === 'admin' ? 'Quản trị viên' : 'Khách hàng' }}
                  </span>
                </td>
                <td class="text-end pe-4">
                  <!-- Nút Đơn hàng -->
                  <Link :href="`/admin/orders?user_id=${customer.id}`" class="btn btn-sm btn-light text-success me-1" title="Xem đơn hàng">
                    <i class="fa-solid fa-box-open"></i>
                  </Link>
                  <!-- Nút Sửa vai trò -->
                  <button class="btn btn-sm btn-light text-primary me-1" @click="openRoleModal(customer)" title="Phân quyền">
                    <i class="fa-solid fa-user-shield"></i>
                  </button>
                  <!-- Nút Xoá -->
                  <button class="btn btn-sm btn-light text-danger" @click="deleteCustomer(customer.id)" title="Xoá khách hàng">
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="customers.data.length === 0">
                <td colspan="6" class="text-center py-5 text-muted">Không tìm thấy khách hàng nào</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center"
        v-if="customers.total > 0">
        <div class="text-muted small">
          Hiển thị từ {{ customers.from }} đến {{ customers.to }} trong tổng số {{ customers.total }} khách hàng
        </div>
        <nav aria-label="Page navigation" class="d-flex align-items-center gap-2">
          <!-- Nút Trước -->
          <Link v-if="customers.links[0].url" :href="customers.links[0].url" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm d-flex align-items-center gap-2 fw-medium">
            <i class="fa-solid fa-chevron-left fa-xs"></i> Trước
          </Link>
          <button v-else class="btn btn-sm btn-light border-0 rounded-pill px-3 shadow-sm text-muted d-flex align-items-center gap-2 fw-medium" disabled>
            <i class="fa-solid fa-chevron-left fa-xs"></i> Trước
          </button>

          <!-- Các trang số -->
          <ul class="pagination pagination-sm mb-0 d-flex gap-1">
            <template v-for="(link, index) in customers.links.slice(1, -1)" :key="index">
              <li class="page-item" :class="{ active: link.active }">
                <Link v-if="link.url" :href="link.url" class="page-link border-0 rounded shadow-sm px-3 fw-medium"
                  :class="{ 'bg-dark text-white': link.active, 'bg-light text-dark': !link.active }" v-html="link.label">
                </Link>
                <span v-else class="page-link border-0 rounded text-muted shadow-sm bg-light px-3 fw-medium" v-html="link.label"></span>
              </li>
            </template>
          </ul>

          <!-- Nút Sau -->
          <Link v-if="customers.links[customers.links.length - 1].url" :href="customers.links[customers.links.length - 1].url" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm d-flex align-items-center gap-2 fw-medium">
            Sau <i class="fa-solid fa-chevron-right fa-xs"></i>
          </Link>
          <button v-else class="btn btn-sm btn-light border-0 rounded-pill px-3 shadow-sm text-muted d-flex align-items-center gap-2 fw-medium" disabled>
            Sau <i class="fa-solid fa-chevron-right fa-xs"></i>
          </button>
        </nav>
      </div>
    </div>

    <!-- Role Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1" ref="roleModalRef">
      <div class="modal-dialog modal-dialog-centered">
        <form @submit.prevent="submitRole" class="modal-content border-0 shadow">
          <div class="modal-header border-bottom-0 bg-light">
            <h6 class="modal-title fw-bold">Phân quyền: {{ selectedCustomer?.name }}</h6>
            <button type="button" class="btn-close" @click="closeRoleModal"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fw-medium">Chọn vai trò mới</label>
              <select class="form-select" v-model="roleForm.role" required>
                <option value="user">Khách hàng (User)</option>
                <option value="admin">Quản trị viên (Admin)</option>
              </select>
              <div class="form-text text-danger mt-2" v-if="roleForm.role === 'admin'">
                <i class="fa-solid fa-triangle-exclamation"></i> Cảnh báo: Cấp quyền Admin sẽ cho phép tài khoản này truy cập vào trang Quản trị.
              </div>
            </div>
          </div>
          <div class="modal-footer border-top-0 bg-light">
            <button type="button" class="btn btn-light border px-4 rounded-pill" @click="closeRoleModal">Huỷ</button>
            <button type="submit" class="btn btn-dark px-4 rounded-pill" :disabled="roleForm.processing">
              Lưu thay đổi
            </button>
          </div>
        </form>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import Layout from './Layout.vue';

const props = defineProps({
  customers: Object,
  filters: Object,
});

const searchQuery = ref(props.filters.search || '');
let searchTimeout = null;

const debouncedSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    router.get('/admin/customers', { search: searchQuery.value }, { preserveState: true });
  }, 400);
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('vi-VN');
};

const roleModalRef = ref(null);
let bootstrapRoleModal = null;
const selectedCustomer = ref(null);

const roleForm = useForm({
  role: 'user',
});

onMounted(() => {
  bootstrapRoleModal = new bootstrap.Modal(roleModalRef.value);
});

const openRoleModal = (customer) => {
  selectedCustomer.value = customer;
  roleForm.role = customer.role;
  roleForm.clearErrors();
  bootstrapRoleModal.show();
};

const closeRoleModal = () => {
  bootstrapRoleModal.hide();
  selectedCustomer.value = null;
};

const submitRole = () => {
  if (!selectedCustomer.value) return;
  roleForm.put(`/admin/customers/${selectedCustomer.value.id}`, {
    onSuccess: () => {
      closeRoleModal();
      if (window.showToast) window.showToast('Đã phân quyền thành công!');
    }
  });
};

const deleteCustomer = (id) => {
  Swal.fire({
    title: 'Xóa tài khoản này?',
    text: "Mọi dữ liệu cá nhân sẽ bị xóa và không thể khôi phục! (Không thể xoá nếu có đơn hàng)",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Đồng ý xóa',
    cancelButtonText: 'Hủy'
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(`/admin/customers/${id}`, {
        onSuccess: () => {
            if (window.showToast) window.showToast('Đã xóa khách hàng!');
        }
      });
    }
  });
};
</script>
