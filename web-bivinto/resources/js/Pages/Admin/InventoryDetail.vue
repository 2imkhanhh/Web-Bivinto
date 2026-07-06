<template>
  <Layout :title="`Kho hàng: ${product.name}`">
    <div class="mb-3">
      <Link href="/admin/inventory" class="text-decoration-none text-dark">
        <i class="fa-solid fa-arrow-left me-2"></i> Quay lại danh sách
      </Link>
    </div>

    <!-- Success/Error Message -->
    <div v-if="$page.props.flash && $page.props.flash.success" class="alert alert-success alert-dismissible fade show"
      role="alert">
      {{ $page.props.flash.success }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div v-if="$page.props.flash && $page.props.flash.error" class="alert alert-danger alert-dismissible fade show"
      role="alert">
      {{ $page.props.flash.error }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-white border-bottom py-3">
        <h6 class="m-0 fw-bold"><i class="fa-solid fa-boxes-stacked me-2"></i> Chi tiết tồn kho - {{ product.name }}
        </h6>
      </div>
      <div class="card-body p-0">
        <form @submit.prevent="saveChanges">
          <div v-for="(color, cIdx) in product.colors" :key="color.id" class="border-bottom">
            <!-- Color Header -->
            <div class="px-4 py-3 bg-light d-flex align-items-center gap-2">
              <span class="d-inline-block rounded-circle border"
                :style="{ backgroundColor: color.color_code, width: '20px', height: '20px' }"></span>
              <span class="fw-bold">{{ color.color_name }}</span>
              <span class="text-muted small ms-2">({{ color.sizes ? color.sizes.length : 0 }} size)</span>
            </div>

            <!-- Size Table -->
            <div class="table-responsive">
              <table class="table mb-0 align-middle">
                <thead>
                  <tr class="small text-muted">
                    <th class="ps-4 border-0" style="width: 25%;">Kích thước</th>
                    <th class="border-0 text-center" style="width: 20%;">Tồn kho hiện tại</th>
                    <th class="border-0 text-center" style="width: 20%;">Số lượng mới</th>
                    <th class="border-0" style="width: 25%;">Ghi chú</th>
                    <th class="border-0 text-center pe-4" style="width: 10%;">Lịch sử</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="size in color.sizes" :key="size.id">
                    <td class="ps-4 fw-medium">{{ size.size_name }}</td>
                    <td class="text-center">
                      <span class="badge rounded-pill px-2 py-1"
                        :class="size.stock <= 0 ? 'bg-danger-subtle text-danger' : size.stock <= 10 ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success'">
                        {{ size.stock }}
                      </span>
                    </td>
                    <td class="text-center">
                      <input type="number" class="form-control form-control-sm text-center mx-auto" style="width: 100px;"
                        min="0" v-model.number="adjustments[size.id].new_stock">
                    </td>
                    <td>
                      <input type="text" class="form-control form-control-sm" placeholder="Ghi chú..."
                        v-model="adjustments[size.id].note">
                    </td>
                    <td class="text-center pe-4">
                      <button type="button" class="btn btn-sm btn-light" @click="showHistory(size.id, size.size_name, color.color_name)"
                        title="Xem lịch sử">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="p-4 d-flex justify-content-end">
            <button type="submit" class="btn btn-dark px-4" :disabled="saving">
              <span v-if="saving" class="spinner-border spinner-border-sm me-2" role="status"></span>
              <i v-else class="fa-solid fa-floppy-disk me-2"></i> Lưu thay đổi
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- History Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" ref="historyModalRef">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
          <div class="modal-header bg-light">
            <h6 class="modal-title fw-bold">
              <i class="fa-solid fa-clock-rotate-left me-2"></i> Lịch sử thay đổi kho - {{ historyLabel }}
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-0">
            <div v-if="loadingHistory" class="text-center py-5">
              <div class="spinner-border text-dark" role="status"></div>
              <p class="text-muted mt-2">Đang tải...</p>
            </div>
            <table v-else-if="histories.length > 0" class="table table-hover mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th class="ps-4">Thời gian</th>
                  <th>Loại</th>
                  <th class="text-center">Số lượng</th>
                  <th class="text-center">Trước</th>
                  <th class="text-center">Sau</th>
                  <th>Ghi chú</th>
                  <th>Người thực hiện</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="h in histories" :key="h.id">
                  <td class="ps-4 small">{{ formatDate(h.created_at) }}</td>
                  <td>
                    <span class="badge" :class="getTypeBadge(h.type)">{{ getTypeText(h.type) }}</span>
                  </td>
                  <td class="text-center fw-bold" :class="h.quantity > 0 ? 'text-success' : 'text-danger'">
                    {{ h.quantity > 0 ? '+' : '' }}{{ h.quantity }}
                  </td>
                  <td class="text-center text-muted">{{ h.stock_before }}</td>
                  <td class="text-center fw-medium">{{ h.stock_after }}</td>
                  <td class="small">{{ h.note || '-' }}</td>
                  <td class="small">{{ h.user ? h.user.name : 'Hệ thống' }}</td>
                </tr>
              </tbody>
            </table>
            <div v-else class="text-center py-5 text-muted">
              <i class="fa-regular fa-folder-open fs-1 mb-3"></i>
              <p>Chưa có lịch sử thay đổi nào</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script>
import { Link, router } from '@inertiajs/vue3';
import Layout from './Layout.vue';

export default {
  components: { Layout, Link },
  props: {
    product: Object,
  },
  data() {
    const adjustments = {};
    if (this.product.colors) {
      this.product.colors.forEach(color => {
        if (color.sizes) {
          color.sizes.forEach(size => {
            adjustments[size.id] = {
              product_size_id: size.id,
              new_stock: size.stock,
              note: '',
            };
          });
        }
      });
    }

    return {
      adjustments,
      saving: false,
      histories: [],
      loadingHistory: false,
      historyLabel: '',
      historyModal: null,
    };
  },
  mounted() {
    this.historyModal = new bootstrap.Modal(this.$refs.historyModalRef);
  },
  methods: {
    saveChanges() {
      // Only send adjustments where stock has actually changed
      const changedAdjustments = [];
      if (this.product.colors) {
        this.product.colors.forEach(color => {
          if (color.sizes) {
            color.sizes.forEach(size => {
              const adj = this.adjustments[size.id];
              if (adj && adj.new_stock !== size.stock) {
                changedAdjustments.push(adj);
              }
            });
          }
        });
      }

      if (changedAdjustments.length === 0) {
        window.showToast && window.showToast('Không có thay đổi nào để lưu.', 'warning');
        return;
      }

      this.saving = true;
      router.put('/admin/inventory/update', { adjustments: changedAdjustments }, {
        preserveScroll: true,
        onFinish: () => {
          this.saving = false;
        },
      });
    },
    async showHistory(sizeId, sizeName, colorName) {
      this.historyLabel = `${colorName} - ${sizeName}`;
      this.loadingHistory = true;
      this.histories = [];
      this.historyModal.show();

      try {
        const response = await fetch(`/admin/inventory/history/${sizeId}`, {
          headers: { 'Accept': 'application/json' },
        });
        this.histories = await response.json();
      } catch (e) {
        this.histories = [];
      } finally {
        this.loadingHistory = false;
      }
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('vi-VN', {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit',
      });
    },
    getTypeBadge(type) {
      return {
        'bg-success-subtle text-success': type === 'in',
        'bg-danger-subtle text-danger': type === 'out',
        'bg-info-subtle text-info': type === 'adjust',
      };
    },
    getTypeText(type) {
      const types = { in: 'Nhập kho', out: 'Xuất kho', adjust: 'Điều chỉnh' };
      return types[type] || type;
    },
  },
};
</script>

<style scoped>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
