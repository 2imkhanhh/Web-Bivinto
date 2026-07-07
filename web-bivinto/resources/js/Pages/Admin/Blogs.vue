<template>
  <Layout title="Quản lý Bài viết">

    <Head title="Bài viết" />
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-dark">Danh sách Bài viết</h5>
        <Link href="/admin/blogs/create" class="btn btn-dark btn-sm rounded-pill px-3">
          <i class="fa-solid fa-plus me-1"></i> Thêm mới
        </Link>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="border-0 px-4" width="5%">STT</th>
                <th class="border-0">Tiêu đề</th>
                <th class="border-0">Ảnh bìa</th>
                <th class="border-0">Mô tả ngắn</th>
                <th class="border-0 text-center">Nổi bật</th>
                <th class="border-0">Trạng thái</th>
                <th class="border-0">Ngày đăng</th>
                <th class="border-0 text-center" width="15%">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(blog, index) in blogs.data" :key="blog.id">
                <td class="px-4 text-muted fw-bold">
                  {{ blog.position > 0 ? blog.position : '--' }}
                </td>
                <td class="fw-medium text-dark">
                  <div class="text-truncate" style="max-width: 250px;" :title="blog.title">{{ blog.title }}</div>
                </td>
                <td>
                  <img v-if="blog.image_path" :src="'/storage/' + blog.image_path"
                    class="rounded object-fit-cover shadow-sm"
                    style="width: 60px; height: 40px; object-position: top;" />
                  <div v-else
                    class="bg-light rounded border d-flex align-items-center justify-content-center text-muted"
                    style="width: 60px; height: 40px;">
                    <i class="fa-regular fa-image"></i>
                  </div>
                </td>
                <td>
                  <div class="text-truncate text-muted small" style="max-width: 200px;" :title="blog.excerpt">{{
                    blog.excerpt || 'Không có' }}</div>
                </td>
                <td class="text-center">
                  <i v-if="blog.is_featured" class="fa-solid fa-star text-warning"></i>
                  <i v-else class="fa-regular fa-star text-muted"></i>
                </td>
                <td>
                  <span v-if="blog.status === 'Published' || blog.status === 'published'"
                    class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Đã xuất bản</span>
                  <span v-else class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">Bản nháp</span>
                </td>
                <td class="text-muted small">
                  {{ new Date(blog.created_at).toLocaleDateString('vi-VN') }}
                </td>
                <td class="text-center">
                  <Link :href="'/admin/blogs/' + blog.id + '/edit'" class="btn btn-sm btn-light text-primary me-1"
                    title="Sửa">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </Link>
                  <button @click="deleteBlog(blog.id)" class="btn btn-sm btn-light text-danger" title="Xóa">
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="blogs.data.length === 0">
                <td colspan="8" class="text-center py-4 text-muted">
                  Chưa có bài viết nào.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer bg-white border-0 py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center"
        v-if="blogs.total > 0">
        <div class="text-muted small mb-2 mb-sm-0">
          Hiển thị từ {{ blogs.from }} đến {{ blogs.to }} trong tổng số {{ blogs.total }} bài viết.
        </div>
        <nav aria-label="Page navigation" class="d-flex align-items-center gap-2">
          <!-- Nút Trước -->
          <Link v-if="blogs.links[0].url" :href="blogs.links[0].url" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm d-flex align-items-center gap-2 fw-medium">
            <i class="fa-solid fa-chevron-left fa-xs"></i> Trước
          </Link>
          <button v-else class="btn btn-sm btn-light border-0 rounded-pill px-3 shadow-sm text-muted d-flex align-items-center gap-2 fw-medium" disabled>
            <i class="fa-solid fa-chevron-left fa-xs"></i> Trước
          </button>

          <!-- Các trang số -->
          <ul class="pagination pagination-sm mb-0 d-flex gap-1">
            <template v-for="(link, index) in blogs.links.slice(1, -1)" :key="index">
              <li class="page-item" :class="{ active: link.active }">
                <Link v-if="link.url" :href="link.url" class="page-link border-0 rounded shadow-sm px-3 fw-medium"
                  :class="{ 'bg-dark text-white': link.active, 'bg-light text-dark': !link.active }" v-html="link.label">
                </Link>
                <span v-else class="page-link border-0 rounded text-muted shadow-sm bg-light px-3 fw-medium" v-html="link.label"></span>
              </li>
            </template>
          </ul>

          <!-- Nút Sau -->
          <Link v-if="blogs.links[blogs.links.length - 1].url" :href="blogs.links[blogs.links.length - 1].url" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm d-flex align-items-center gap-2 fw-medium">
            Sau <i class="fa-solid fa-chevron-right fa-xs"></i>
          </Link>
          <button v-else class="btn btn-sm btn-light border-0 rounded-pill px-3 shadow-sm text-muted d-flex align-items-center gap-2 fw-medium" disabled>
            Sau <i class="fa-solid fa-chevron-right fa-xs"></i>
          </button>
        </nav>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Layout from './Layout.vue';


const props = defineProps({
  blogs: Object
});

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('vi-VN', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  }).format(date);
};

const deleteBlog = (id) => {
  Swal.fire({
    title: 'Xóa bài viết?',
    text: "Bạn không thể hoàn tác hành động này!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Đồng ý xóa',
    cancelButtonText: 'Hủy'
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(`/admin/blogs/${id}`, {
        onSuccess: () => {
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Đã xóa bài viết',
            showConfirmButton: false,
            timer: 3000
          });
        }
      });
    }
  });
};
</script>
