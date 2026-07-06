<template>
  <Layout title="Quản lý Đơn hàng">
    <Head title="Đơn hàng" />

    <!-- Success Message -->
    <div v-if="$page.props.flash && $page.props.flash.success" class="alert alert-success alert-dismissible fade show mb-3"
      role="alert">
      {{ $page.props.flash.success }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-dark">Danh sách Đơn hàng</h5>
        <div>
          <select class="form-select d-inline-block w-auto form-select-sm" v-model="filterStatus" @change="filterOrders">
            <option value="all">Tất cả trạng thái</option>
            <option value="pending">Chờ xác nhận</option>
            <option value="confirmed">Đã xác nhận (Chờ lấy hàng)</option>
            <option value="shipping">Đang giao hàng</option>
            <option value="completed">Hoàn thành</option>
            <option value="cancelled">Đã hủy</option>
          </select>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th class="ps-4" style="width: 5%;">STT</th>
                <th>Mã đơn hàng</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th class="text-end pe-4">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(order, index) in orders.data" :key="order.id">
                <td class="ps-4 text-muted">{{ (orders.current_page - 1) * orders.per_page + index + 1 }}</td>
                <td class="">#{{ order.order_code }}</td>
                <td>
                  <div>{{ order.name }}</div>
                  <div class="text-muted small">{{ order.phone }}</div>
                </td>
                <td>{{ formatDate(order.created_at) }}</td>
                <td class="fw-medium text-danger">{{ formatCurrency(order.total_amount) }}</td>
                <td>
                  <span class="badge" :class="getStatusBadgeClass(order.status)">
                    {{ getStatusText(order.status) }}
                  </span>
                </td>
                <td class="text-end pe-4">
                  <Link :href="`/admin/orders/${order.id}`" class="btn btn-sm btn-outline-dark">
                  <i class="fa-solid fa-eye"></i> Xem
                  </Link>
                </td>
              </tr>
              <tr v-if="orders.data.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">Không có đơn hàng nào</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center"
        v-if="orders.total > 0">
        <div class="text-muted small">
          Hiển thị từ {{ orders.from }} đến {{ orders.to }} trong tổng số {{ orders.total }} đơn hàng
        </div>
        <nav aria-label="Page navigation">
          <ul class="pagination pagination-sm mb-0 d-flex gap-1">
            <li class="page-item" :class="{ disabled: !link.url, active: link.active }"
              v-for="(link, index) in orders.links" :key="index">
              <Link v-if="link.url" :href="link.url" class="page-link border-0 rounded text-dark shadow-sm"
                :class="{ 'bg-dark text-white': link.active, 'bg-light': !link.active }" v-html="link.label">
              </Link>
              <span v-else class="page-link border-0 rounded text-muted shadow-sm bg-light" v-html="link.label"></span>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </Layout>
</template>

<script>
import { Head, Link, router } from '@inertiajs/vue3';
import Layout from './Layout.vue';

export default {
  components: {
    Layout,
    Link,
    Head,
  },
  props: {
    orders: Object,
    filters: Object,
  },
  data() {
    return {
      filterStatus: this.filters.status || 'all',
    };
  },
  methods: {
    filterOrders() {
      router.get(
        '/admin/orders',
        { status: this.filterStatus },
        { preserveState: true }
      );
    },
    formatCurrency(value) {
      return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
      }).format(value);
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      });
    },
    getStatusText(status) {
      const statuses = {
        pending: 'Chờ xác nhận',
        confirmed: 'Đã xác nhận',
        shipping: 'Đang giao hàng',
        completed: 'Hoàn thành',
        cancelled: 'Đã hủy',
      };
      return statuses[status] || status;
    },
    getStatusBadgeClass(status) {
      return {
        'bg-warning text-dark': status === 'pending',
        'bg-info text-dark': status === 'confirmed',
        'bg-primary': status === 'shipping',
        'bg-success': status === 'completed',
        'bg-secondary': status === 'cancelled',
      };
    },
  },
};
</script>
