<template>
  <Layout :title="`Chi tiết Đơn hàng #${order.order_code}`">
    <div class="mb-3">
      <Link href="/admin/orders" class="text-decoration-none text-dark">
      <i class="fa-solid fa-arrow-left me-2"></i> Quay lại danh sách
      </Link>
    </div>

    <!-- Success Message -->
    <div v-if="$page.props.flash && $page.props.flash.success" class="alert alert-success alert-dismissible fade show"
      role="alert">
      {{ $page.props.flash.success }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <!-- Danh sách sản phẩm -->
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold"><i class="fa-solid fa-box-open me-2"></i> Sản phẩm trong đơn</h6>
            <span class="badge bg-secondary">{{ order.items.length }} sản phẩm</span>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table mb-0 align-middle">
                <thead class="table-light">
                  <tr>
                    <th class="ps-4">Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>SL</th>
                    <th class="text-end pe-4">Thành tiền</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in order.items" :key="item.id">
                    <td class="ps-4">
                      <div class="d-flex align-items-center">
                        <div>
                          <h6 class="mb-1 text-dark" style="font-size: 0.9rem;">{{ item.product.name }}</h6>
                          <div class="text-muted small" v-if="item.color || item.size">
                            Phân loại: {{ item.color ? item.color.color_name : '' }} / {{ item.size ?
                              item.size.size_name : '' }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>{{ formatCurrency(item.price) }}</td>
                    <td>x{{ item.quantity }}</td>
                    <td class="text-end fw-bold pe-4">{{ formatCurrency(item.total) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer bg-white py-3 border-top">
            <div class="row justify-content-end">
              <div class="col-md-6 col-lg-5">
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted">Tổng tiền hàng:</span>
                  <span>{{ formatCurrency(order.subtotal) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted">Phí vận chuyển:</span>
                  <span>{{ order.shipping_fee == 0 ? '0đ' : formatCurrency(order.shipping_fee) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-bold fs-6">Thành tiền:</span>
                  <span class="fs-5 fw-bold text-danger">{{ formatCurrency(order.total_amount) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <!-- Thông tin khách hàng & Giao hàng -->
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3">
            <h6 class="m-0 fw-bold"><i class="fa-solid fa-address-card me-2"></i> Thông tin giao hàng</h6>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="text-muted small mb-1">Người nhận</label>
              <div>{{ order.name }}</div>
            </div>
            <div class="mb-3">
              <label class="text-muted small mb-1">Số điện thoại</label>
              <div><a :href="`tel:${order.phone}`" class="text-dark text-decoration-none">{{ order.phone }}</a></div>
            </div>
            <div class="mb-3" v-if="order.email">
              <label class="text-muted small mb-1">Email</label>
              <div><a :href="`mailto:${order.email}`" class="text-dark text-decoration-none">{{ order.email }}</a></div>
            </div>
            <div class="mb-3">
              <label class="text-muted small mb-1">Địa chỉ giao hàng</label>
              <div>{{ order.address }}</div>
              <div>{{ order.ward }}, {{ order.district }}, {{ order.province }}</div>
            </div>
            <div v-if="order.note">
              <label class="text-muted small mb-1">Ghi chú của khách hàng</label>
              <div class="p-2 bg-light rounded fst-italic text-muted small">{{ order.note }}</div>
            </div>
          </div>
        </div>

        <!-- Cập nhật trạng thái -->
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold"><i class="fa-solid fa-truck me-2"></i> Trạng thái đơn hàng</h6>
            <span class="badge" :class="getStatusBadgeClass(order.status)">{{ getStatusText(order.status) }}</span>
          </div>
          <div class="card-body">
            <form @submit.prevent="updateStatus">
              <div class="mb-3">
                <label class="form-label text-muted small">Cập nhật trạng thái mới</label>
                <select class="form-select" v-model="form.status">
                  <option value="pending">Chờ xác nhận</option>
                  <option value="confirmed">Đã xác nhận</option>
                  <option value="shipping">Đang giao hàng</option>
                  <option value="completed">Hoàn thành</option>
                  <option value="cancelled">Đã hủy</option>
                </select>
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-dark" :disabled="form.processing">
                  <span v-if="form.processing" class="spinner-border spinner-border-sm me-2" role="status"
                    aria-hidden="true"></span>
                  Lưu thay đổi
                </button>
                <a :href="`/admin/orders/${order.id}/print`" target="_blank" class="btn btn-outline-secondary">
                  <i class="fa-solid fa-print me-2"></i> In hóa đơn
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script>
import { Link, useForm } from '@inertiajs/vue3';
import Layout from './Layout.vue';

export default {
  components: {
    Layout,
    Link,
  },
  props: {
    order: Object,
  },
  setup(props) {
    const form = useForm({
      status: props.order.status,
    });

    const updateStatus = () => {
      form.put(`/admin/orders/${props.order.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          if (window.showToast) {
            window.showToast('Cập nhật trạng thái đơn hàng thành công!');
          }
        },
        onError: () => {
          if (window.showToast) {
            window.showToast('Có lỗi xảy ra khi cập nhật!', 'error');
          }
        }
      });
    };

    return { form, updateStatus };
  },
  methods: {
    formatCurrency(value) {
      return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
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
  }
};
</script>
