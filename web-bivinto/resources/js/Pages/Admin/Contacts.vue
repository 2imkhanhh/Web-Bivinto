<template>
  <Layout title="Quản lý liên hệ">
    <Head title="Liên hệ" />
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-dark">Danh sách liên hệ</h5>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="border-0 px-4" width="5%">STT</th>
                <th class="border-0" width="15%">Họ Tên</th>
                <th class="border-0" width="15%">Điện thoại</th>
                <th class="border-0" width="20%">Email</th>
                <th class="border-0" width="15%">Ngày gửi</th>
                <th class="border-0 text-center" width="15%">Trạng thái</th>
                <th class="border-0 text-center" width="15%">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(contact, index) in contacts.data" :key="contact.id">
                <td class="px-4 text-muted">{{ (contacts.current_page - 1) * contacts.per_page + index + 1 }}</td>
                <td><div class="fw-medium text-dark">{{ contact.name }}</div></td>
                <td>{{ contact.phone }}</td>
                <td>{{ contact.email || '--' }}</td>
                <td class="text-muted">{{ formatDate(contact.created_at) }}</td>
                <td class="text-center">
                  <span v-if="contact.status === 'resolved'" class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Đã xử lý</span>
                  <span v-else class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill">Chờ xử lý</span>
                </td>
                <td class="text-center">
                  <button class="btn btn-sm btn-light text-primary me-1" @click="viewContact(contact)" title="Xem chi tiết">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                  <button v-if="contact.status === 'pending'" class="btn btn-sm btn-light text-success me-1" @click="resolveContact(contact.id)" title="Đánh dấu đã xử lý">
                    <i class="fa-solid fa-check"></i>
                  </button>
                  <button class="btn btn-sm btn-light text-danger" @click="deleteContact(contact.id)" title="Xóa">
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="contacts.data.length === 0">
                <td colspan="7" class="text-center py-5 text-muted">
                  <i class="fa-regular fa-envelope-open fs-1 mb-3 text-light"></i><br>
                  Chưa có liên hệ nào.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center" v-if="contacts.total > 0">
        <div class="text-muted small">
          Hiển thị từ {{ contacts.from }} đến {{ contacts.to }} trong tổng số {{ contacts.total }} liên hệ
        </div>
        <nav aria-label="Page navigation" class="d-flex align-items-center gap-2">
          <!-- Nút Trước -->
          <Link v-if="contacts.links[0].url" :href="contacts.links[0].url"
            class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm d-flex align-items-center gap-2 fw-medium">
            <i class="fa-solid fa-chevron-left fa-xs"></i> Trước
          </Link>
          <button v-else
            class="btn btn-sm btn-light border-0 rounded-pill px-3 shadow-sm text-muted d-flex align-items-center gap-2 fw-medium"
            disabled>
            <i class="fa-solid fa-chevron-left fa-xs"></i> Trước
          </button>

          <!-- Các trang số -->
          <ul class="pagination pagination-sm mb-0 d-flex gap-1">
            <template v-for="(link, index) in contacts.links.slice(1, -1)" :key="index">
              <li class="page-item" :class="{ active: link.active }">
                <Link v-if="link.url" :href="link.url" class="page-link border-0 rounded shadow-sm px-3 fw-medium"
                  :class="{ 'bg-dark text-white': link.active, 'bg-light text-dark': !link.active }"
                  v-html="link.label">
                </Link>
                <span v-else class="page-link border-0 rounded text-muted shadow-sm bg-light px-3 fw-medium"
                  v-html="link.label"></span>
              </li>
            </template>
          </ul>

          <!-- Nút Sau -->
          <Link v-if="contacts.links[contacts.links.length - 1].url"
            :href="contacts.links[contacts.links.length - 1].url"
            class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm d-flex align-items-center gap-2 fw-medium">
            Sau <i class="fa-solid fa-chevron-right fa-xs"></i>
          </Link>
          <button v-else
            class="btn btn-sm btn-light border-0 rounded-pill px-3 shadow-sm text-muted d-flex align-items-center gap-2 fw-medium"
            disabled>
            Sau <i class="fa-solid fa-chevron-right fa-xs"></i>
          </button>
        </nav>
      </div>
    </div>

    <!-- Modal Xem Chi Tiết -->
    <div class="modal fade" id="viewContactModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header border-bottom-0 bg-light">
            <h5 class="modal-title fw-bold">Chi tiết liên hệ #{{ selectedContact?.id }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4 bg-light bg-opacity-50" v-if="selectedContact">
            <div class="card shadow-sm border-0 mb-3">
               <div class="card-body">
                  <div class="row mb-2">
                    <div class="col-4 text-muted fw-medium">Họ và tên:</div>
                    <div class="col-8 text-dark fw-medium">{{ selectedContact.name }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-4 text-muted fw-medium">Số điện thoại:</div>
                    <div class="col-8 text-dark">{{ selectedContact.phone }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-4 text-muted fw-medium">Email:</div>
                    <div class="col-8 text-dark">{{ selectedContact.email || 'Không có' }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-4 text-muted fw-medium">Ngày gửi:</div>
                    <div class="col-8 text-dark">{{ formatDate(selectedContact.created_at) }}</div>
                  </div>
                  <div class="row">
                    <div class="col-4 text-muted fw-medium">Trạng thái:</div>
                    <div class="col-8">
                      <span v-if="selectedContact.status === 'resolved'" class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Đã xử lý</span>
                      <span v-else class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill">Chờ xử lý</span>
                    </div>
                  </div>
               </div>
            </div>
            <div>
              <h6 class="fw-bold mb-2">Nội dung lời nhắn:</h6>
              <div class="p-3 bg-white rounded shadow-sm border" style="white-space: pre-wrap; font-size: 0.95rem;">{{ selectedContact.message }}</div>
            </div>
          </div>
          <div class="modal-footer border-top-0 bg-light">
            <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Đóng</button>
            <button v-if="selectedContact?.status === 'pending'" type="button" class="btn btn-success px-4" @click="resolveContact(selectedContact.id)">
              <i class="fa-solid fa-check me-1"></i> Đánh dấu đã xử lý
            </button>
          </div>
        </div>
      </div>
    </div>

  </Layout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Layout from './Layout.vue';

const props = defineProps({
  contacts: Object
});

const selectedContact = ref(null);
let viewModal = null;

const initModal = () => {
  if (!viewModal && typeof window !== 'undefined') {
    const modalEl = document.getElementById('viewContactModal');
    if (modalEl) {
      viewModal = new bootstrap.Modal(modalEl);
    }
  }
};

const formatDate = (date) => {
  if (!date) return '';
  const d = new Date(date);
  const dd = String(d.getDate()).padStart(2, '0');
  const mm = String(d.getMonth() + 1).padStart(2, '0');
  const yyyy = d.getFullYear();
  const hh = String(d.getHours()).padStart(2, '0');
  const min = String(d.getMinutes()).padStart(2, '0');
  return `${dd}/${mm}/${yyyy} ${hh}:${min}`;
};

const viewContact = (contact) => {
  selectedContact.value = contact;
  initModal();
  if (viewModal) {
    viewModal.show();
  }
};

const resolveContact = (id) => {
  Swal.fire({
    title: 'Xác nhận xử lý?',
    text: "Bạn xác nhận đã xử lý liên hệ này?",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#198754',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Đồng ý',
    cancelButtonText: 'Hủy'
  }).then((result) => {
    if (result.isConfirmed) {
      router.put(`/admin/contacts/${id}`, { status: 'resolved' }, {
        preserveScroll: true,
        onSuccess: () => {
          if (viewModal) {
            viewModal.hide();
          }
          window.showToast('Cập nhật trạng thái thành công');
        }
      });
    }
  });
};

const deleteContact = (id) => {
  Swal.fire({
    title: 'Xác nhận xóa?',
    text: "Bạn có chắc chắn muốn xóa liên hệ này? Dữ liệu không thể khôi phục!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Xóa',
    cancelButtonText: 'Hủy'
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(`/admin/contacts/${id}`, {
        preserveScroll: true,
        onSuccess: () => {
          window.showToast('Xóa liên hệ thành công');
        }
      });
    }
  });
};
</script>
