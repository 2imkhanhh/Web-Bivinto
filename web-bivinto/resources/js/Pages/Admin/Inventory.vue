<template>
  <Layout title="Quản lý Kho hàng">
    <Head title="Kho hàng" />

    <div class="card shadow-sm border-0">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-dark">Tổng quan kho hàng</h5>
        <div>
          <input type="text" class="form-control d-inline-block form-control-sm" style="width: 300px;" placeholder="Tìm kiếm sản phẩm..."
            v-model="searchQuery" @input="debouncedSearch">
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th class="ps-4" style="width: 5%;">STT</th>
                <th>Sản phẩm</th>
                <th class="text-center">Biến thể</th>
                <th class="text-center">Tổng tồn kho</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-end pe-4">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(product, index) in products.data" :key="product.id">
                <td class="ps-4 text-muted">{{ (products.current_page - 1) * products.per_page + index + 1 }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <img v-if="product.images && product.images.length > 0"
                      :src="'/storage/' + getPrimaryImage(product)" :alt="product.name"
                      class="rounded me-3 object-fit-cover" style="width: 40px; height: 40px; object-position: top;">
                    <div v-else class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-muted"
                      style="width: 40px; height: 40px;">
                      <i class="fa-regular fa-image"></i>
                    </div>
                    <div class="fw-medium text-dark">{{ product.name }}</div>
                  </div>
                </td>
                <td class="text-center">{{ countVariants(product) }}</td>
                <td class="text-center fw-bold" :class="getStockClass(product.total_stock)">
                  {{ product.total_stock }}
                </td>
                <td class="text-center">
                  <span class="badge rounded-pill px-2 py-1" :class="getStatusBadge(product.total_stock)">
                    {{ getStatusText(product.total_stock) }}
                  </span>
                </td>
                <td class="text-end pe-4">
                  <Link :href="`/admin/inventory/${product.id}`" class="btn btn-sm btn-outline-dark">
                    <i class="fa-solid fa-boxes-stacked me-1"></i> Chi tiết
                  </Link>
                </td>
              </tr>
              <tr v-if="products.data.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">Không tìm thấy sản phẩm nào</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center"
        v-if="products.total > 0">
        <div class="text-muted small">
          Hiển thị từ {{ products.from }} đến {{ products.to }} trong tổng số {{ products.total }} sản phẩm
        </div>
        <nav aria-label="Page navigation" class="d-flex align-items-center gap-2">
          <!-- Nút Trước -->
          <Link v-if="products.links[0].url" :href="products.links[0].url" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm d-flex align-items-center gap-2 fw-medium">
            <i class="fa-solid fa-chevron-left fa-xs"></i> Trước
          </Link>
          <button v-else class="btn btn-sm btn-light border-0 rounded-pill px-3 shadow-sm text-muted d-flex align-items-center gap-2 fw-medium" disabled>
            <i class="fa-solid fa-chevron-left fa-xs"></i> Trước
          </button>

          <!-- Các trang số -->
          <ul class="pagination pagination-sm mb-0 d-flex gap-1">
            <template v-for="(link, index) in products.links.slice(1, -1)" :key="index">
              <li class="page-item" :class="{ active: link.active }">
                <Link v-if="link.url" :href="link.url" class="page-link border-0 rounded shadow-sm px-3 fw-medium"
                  :class="{ 'bg-dark text-white': link.active, 'bg-light text-dark': !link.active }" v-html="link.label">
                </Link>
                <span v-else class="page-link border-0 rounded text-muted shadow-sm bg-light px-3 fw-medium" v-html="link.label"></span>
              </li>
            </template>
          </ul>

          <!-- Nút Sau -->
          <Link v-if="products.links[products.links.length - 1].url" :href="products.links[products.links.length - 1].url" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm d-flex align-items-center gap-2 fw-medium">
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

<script>
import { Head, Link, router } from '@inertiajs/vue3';
import Layout from './Layout.vue';

let searchTimeout = null;

export default {
  components: { Layout, Link, Head },
  props: {
    products: Object,
    filters: Object,
  },
  data() {
    return {
      searchQuery: this.filters?.search || '',
    };
  },
  methods: {
    debouncedSearch() {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        router.get('/admin/inventory', { search: this.searchQuery }, { preserveState: true });
      }, 400);
    },

    getPrimaryImage(product) {
      if (!product.images || product.images.length === 0) return '';
      const primary = product.images.find(img => img.is_primary);
      return primary ? primary.image_path : product.images[0].image_path;
    },
    countVariants(product) {
      if (!product.colors) return 0;
      let count = 0;
      product.colors.forEach(c => {
        count += c.sizes ? c.sizes.length : 0;
      });
      return count;
    },
    getStockClass(stock) {
      if (stock <= 0) return 'text-danger';
      if (stock <= 10) return 'text-warning';
      return 'text-success';
    },
    getStatusBadge(stock) {
      if (stock <= 0) return 'bg-danger-subtle text-danger';
      if (stock <= 10) return 'bg-warning-subtle text-warning';
      return 'bg-success-subtle text-success';
    },
    getStatusText(stock) {
      if (stock <= 0) return 'Hết hàng';
      if (stock <= 10) return 'Sắp hết';
      return 'Còn hàng';
    },
  },
};
</script>
