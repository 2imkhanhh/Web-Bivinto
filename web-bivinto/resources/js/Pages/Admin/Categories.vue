<template>
  <Layout title="Quản lý Danh mục">
    <Head title="Danh mục" />
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-dark">Danh sách Danh mục</h5>
        <button class="btn btn-dark btn-sm rounded-pill px-3" @click="openCreateModal">
          <i class="fa-solid fa-plus me-1"></i> Thêm mới
        </button>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="border-0 px-4" width="5%">STT</th>
                <th class="border-0">Tên danh mục</th>
                <th class="border-0 text-center">Nổi bật</th>
                <th class="border-0">Danh mục cha</th>
                <th class="border-0">Trạng thái</th>
                <th class="border-0 text-center" width="15%">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(category, index) in categories.data" :key="category.id">
                <td class="px-4 text-muted">{{ (categories.current_page - 1) * categories.per_page + index + 1 }}</td>
                <td class="fw-medium text-dark">{{ category.name }}</td>
                <td class="text-center">
                  <i v-if="category.is_featured" class="fa-solid fa-star text-warning"></i>
                  <i v-else class="fa-regular fa-star text-muted"></i>
                </td>
                <td>
                  <span v-if="category.parent" class="badge bg-secondary">{{ category.parent.name }}</span>
                  <span v-else class="text-muted fst-italic">-- Trống --</span>
                </td>
                <td>
                  <span v-if="category.status === 'active'" class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Hoạt động</span>
                  <span v-else class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">Đang ẩn</span>
                </td>
                <td class="text-center">
                  <button class="btn btn-sm btn-light text-primary me-1" @click="openEditModal(category)" title="Sửa">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </button>
                  <button class="btn btn-sm btn-light text-danger" @click="deleteCategory(category.id)" title="Xóa">
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="categories.data.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">
                  Chưa có danh mục nào.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center"
        v-if="categories.total > 0">
        <div class="text-muted small">
          Hiển thị từ {{ categories.from }} đến {{ categories.to }} trong tổng số {{ categories.total }} danh mục
        </div>
        <nav aria-label="Page navigation" class="d-flex align-items-center gap-2">
          <!-- Nút Trước -->
          <Link v-if="categories.links[0].url" :href="categories.links[0].url" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm d-flex align-items-center gap-2 fw-medium">
            <i class="fa-solid fa-chevron-left fa-xs"></i> Trước
          </Link>
          <button v-else class="btn btn-sm btn-light border-0 rounded-pill px-3 shadow-sm text-muted d-flex align-items-center gap-2 fw-medium" disabled>
            <i class="fa-solid fa-chevron-left fa-xs"></i> Trước
          </button>

          <!-- Các trang số -->
          <ul class="pagination pagination-sm mb-0 d-flex gap-1">
            <template v-for="(link, index) in categories.links.slice(1, -1)" :key="index">
              <li class="page-item" :class="{ active: link.active }">
                <Link v-if="link.url" :href="link.url" class="page-link border-0 rounded shadow-sm px-3 fw-medium"
                  :class="{ 'bg-dark text-white': link.active, 'bg-light text-dark': !link.active }" v-html="link.label">
                </Link>
                <span v-else class="page-link border-0 rounded text-muted shadow-sm bg-light px-3 fw-medium" v-html="link.label"></span>
              </li>
            </template>
          </ul>

          <!-- Nút Sau -->
          <Link v-if="categories.links[categories.links.length - 1].url" :href="categories.links[categories.links.length - 1].url" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm d-flex align-items-center gap-2 fw-medium">
            Sau <i class="fa-solid fa-chevron-right fa-xs"></i>
          </Link>
          <button v-else class="btn btn-sm btn-light border-0 rounded-pill px-3 shadow-sm text-muted d-flex align-items-center gap-2 fw-medium" disabled>
            Sau <i class="fa-solid fa-chevron-right fa-xs"></i>
          </button>
        </nav>
      </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="categoryModal" tabindex="-1" ref="modalRef">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <form @submit.prevent="submitForm">
            <div class="modal-header border-bottom-0">
              <h5 class="modal-title fw-bold">{{ isEditing ? 'Sửa Danh Mục' : 'Thêm Danh Mục' }}</h5>
              <button type="button" class="btn-close" @click="closeModal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label fw-medium">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.name }" v-model="form.name" required placeholder="Nhập tên danh mục">
                <div class="invalid-feedback" v-if="form.errors.name">{{ form.errors.name }}</div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-medium">Danh mục cha</label>
                <select class="form-select" :class="{ 'is-invalid': form.errors.parent_id }" v-model="form.parent_id">
                  <option :value="null">-- Không có (Danh mục gốc) --</option>
                  <option v-for="parent in parentCategories" :key="parent.id" :value="parent.id">
                    {{ parent.name }}
                  </option>
                </select>
                <div class="invalid-feedback" v-if="form.errors.parent_id">{{ form.errors.parent_id }}</div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-medium">Mô tả</label>
                <textarea class="form-control" v-model="form.description" rows="3" placeholder="Nhập mô tả (không bắt buộc)"></textarea>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-medium">Thứ tự hiển thị</label>
                  <input type="number" class="form-control" v-model="form.display_order">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-medium">Trạng thái</label>
                  <select class="form-select" v-model="form.status">
                    <option value="active">Hoạt động</option>
                    <option value="inactive">Đang ẩn</option>
                  </select>
                </div>
              </div>

              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_featured" v-model="form.is_featured" :true-value="1" :false-value="0">
                <label class="form-check-label fw-medium" for="is_featured">Nổi bật</label>
              </div>
            </div>
            <div class="modal-footer border-top-0 bg-light rounded-bottom">
              <button type="button" class="btn btn-secondary rounded-pill px-4" @click="closeModal">Hủy</button>
              <button type="submit" class="btn btn-dark rounded-pill px-4" :disabled="form.processing">
                <i v-if="form.processing" class="fa-solid fa-spinner fa-spin me-1"></i> Lưu
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import Layout from './Layout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
  categories: Object,
  parentCategories: Array,
});

const modalRef = ref(null);
let bootstrapModal = null;
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
  name: '',
  parent_id: null,
  description: '',
  status: 'active',
  is_featured: 0,
  display_order: 0,
});

onMounted(() => {
  bootstrapModal = new bootstrap.Modal(modalRef.value);
});

const openCreateModal = () => {
  isEditing.value = false;
  editingId.value = null;
  form.reset();
  form.clearErrors();
  bootstrapModal.show();
};

const openEditModal = (category) => {
  isEditing.value = true;
  editingId.value = category.id;
  form.name = category.name;
  form.parent_id = category.parent_id;
  form.description = category.description;
  form.status = category.status;
  form.is_featured = category.is_featured;
  form.display_order = category.display_order;
  form.clearErrors();
  bootstrapModal.show();
};

const closeModal = () => {
  bootstrapModal.hide();
};

const submitForm = () => {
  if (isEditing.value) {
    form.put(`/admin/categories/${editingId.value}`, {
      onSuccess: () => {
        closeModal();
        window.showToast('Cập nhật danh mục thành công!');
      }
    });
  } else {
    form.post('/admin/categories', {
      onSuccess: () => {
        closeModal();
        window.showToast('Thêm danh mục thành công!');
      }
    });
  }
};

const deleteCategory = (id) => {
  Swal.fire({
    title: 'Xóa danh mục?',
    text: "Bạn không thể hoàn tác hành động này!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Đồng ý xóa',
    cancelButtonText: 'Hủy'
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(`/admin/categories/${id}`, {
        onSuccess: () => window.showToast('Đã xóa danh mục!')
      });
    }
  });
};
</script>
