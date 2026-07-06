<template>
  <Layout title="Tổng quan">

    <Head title="Tổng quan" />

    <div class="row g-3 mb-4">
      <!-- Thống kê cơ bản -->
      <div class="col-md-3">
        <div class="card p-3 shadow-sm border-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted mb-1">Doanh thu</h6>
              <h4 class="m-0 text-dark">{{ formatCurrency(totalRevenue) }}</h4>
            </div>
            <div class="text-success">
              <i class="fa-solid fa-money-bill-wave fa-2x opacity-50"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card p-3 shadow-sm border-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted mb-1">Đơn hàng</h6>
              <h4 class="m-0 text-dark">{{ newOrdersCount }}</h4>
            </div>
            <div class="text-primary">
              <i class="fa-solid fa-cart-shopping fa-2x opacity-50"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card p-3 shadow-sm border-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted mb-1">Khách hàng</h6>
              <h4 class="m-0 text-dark">{{ totalCustomers }}</h4>
            </div>
            <div class="text-info">
              <i class="fa-solid fa-users fa-2x opacity-50"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card p-3 shadow-sm border-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted mb-1">Sản phẩm</h6>
              <h4 class="m-0 text-dark">{{ totalProducts }}</h4>
            </div>
            <div class="text-warning">
              <i class="fa-solid fa-box fa-2x opacity-50"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
      <!-- Biểu đồ Doanh thu -->
      <div class="col-md-8">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <h6 class="m-0 font-weight-bold">Biểu đồ Doanh thu</h6>
            <select class="form-select form-select-sm w-auto shadow-none" v-model="chartRange" @change="fetchChartData">
              <option value="7_days">7 ngày qua</option>
              <option value="30_days">30 ngày qua</option>
              <option value="3_months">3 tháng qua</option>
              <option value="6_months">6 tháng qua</option>
              <option value="9_months">9 tháng qua</option>
              <option value="1_year">1 năm qua</option>
            </select>
          </div>
          <div class="card-body">
            <div style="height: 300px; position: relative;">
              <Line v-if="lineChartData.labels" :data="lineChartData" :options="lineChartOptions" />
              <div v-if="isLoadingChart" class="position-absolute top-50 start-50 translate-middle">
                <div class="spinner-border text-secondary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Biểu đồ Trạng thái Đơn hàng -->
      <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-white py-3 border-0">
            <h6 class="m-0 font-weight-bold">Trạng thái Đơn hàng</h6>
          </div>
          <div class="card-body d-flex align-items-center justify-content-center">
            <div style="height: 250px; width: 100%; position: relative;">
              <Doughnut v-if="doughnutChartData.labels" :data="doughnutChartData" :options="doughnutChartOptions" />
              <div v-if="isLoadingChart" class="position-absolute top-50 start-50 translate-middle">
                <div class="spinner-border text-secondary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Tables Row -->
    <div class="row g-3">
      <!-- Đơn hàng gần đây -->
      <div class="col-md-8">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <h6 class="m-0 font-weight-bold">Đơn hàng gần đây</h6>
            <Link href="/admin/orders" class="btn btn-sm btn-outline-secondary">Xem tất cả</Link>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="ps-3">Mã ĐH</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="order in recentOrders" :key="order.id">
                    <td class="ps-3">
                      <Link :href="`/admin/orders/${order.id}`" class="text-dark text-decoration-none fw-medium">#{{
                        order.order_code }}</Link>
                    </td>
                    <td>{{ order.user ? order.user.name : 'Khách vãng lai' }}</td>
                    <td>{{ formatDate(order.created_at) }}</td>
                    <td>{{ formatCurrency(order.total_amount) }}</td>
                    <td>
                      <span class="badge" :class="getStatusBadgeClass(order.status)">{{ getStatusName(order.status)
                      }}</span>
                    </td>
                  </tr>
                  <tr v-if="!recentOrders || recentOrders.length === 0">
                    <td colspan="5" class="text-center py-3 text-muted">Chưa có đơn hàng nào</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Sản phẩm bán chạy -->
      <div class="col-md-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white py-3 border-0">
            <h6 class="m-0 font-weight-bold">Top 5 Bán Chạy</h6>
          </div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush">
              <li v-for="item in topProducts" :key="item.product_id"
                class="list-group-item d-flex justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center">
                  <div class="bg-light rounded p-1 me-3" style="width: 45px; height: 45px;">
                    <img v-if="getProductImage(item.product)" :src="getProductImage(item.product)"
                      class="img-fluid w-100 h-100 object-fit-cover rounded" />
                    <i v-else
                      class="fa-solid fa-box text-secondary d-flex justify-content-center align-items-center h-100"></i>
                  </div>
                  <div style="max-width: 150px;">
                    <h6 class="mb-0 text-truncate" :title="item.product?.name">{{ item.product?.name }}</h6>
                    <small class="text-muted">{{ formatCurrency(item.product?.price) }}</small>
                  </div>
                </div>
                <span class="badge bg-light text-dark border">{{ item.total_sold }} đã bán</span>
              </li>
              <li v-if="!topProducts || topProducts.length === 0" class="list-group-item text-center py-3 text-muted">
                Chưa có dữ liệu thống kê
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Layout from './Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';

// Chart.js imports
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  Filler
} from 'chart.js';
import { Line, Doughnut } from 'vue-chartjs';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  Filler
);

const props = defineProps({
  totalRevenue: Number,
  newOrdersCount: Number,
  totalCustomers: Number,
  totalProducts: Number,
  recentOrders: Array,
  topProducts: Array,
});

const chartRange = ref('7_days');
const isLoadingChart = ref(false);

const lineChartData = ref({});
const doughnutChartData = ref({});

const lineChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    },
    tooltip: {
      callbacks: {
        label: function (context) {
          let label = context.dataset.label || '';
          if (label) {
            label += ': ';
          }
          if (context.parsed.y !== null) {
            label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
          }
          return label;
        }
      }
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      max: 2000000,
      ticks: {
        stepSize: 200000,
        callback: function (value) {
          if (value >= 1000000) {
            return (value / 1000000) + 'Tr';
          } else if (value >= 1000) {
            return (value / 1000) + 'K';
          }
          return value;
        }
      }
    }
  }
};

const doughnutChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'right',
      labels: {
        boxWidth: 12
      }
    }
  }
};

const statusColors = {
  'pending': '#6c757d',
  'confirmed': '#0dcaf0',
  'shipping': '#ffc107',
  'completed': '#198754',
  'cancelled': '#dc3545'
};

const statusNames = {
  'pending': 'Chờ xác nhận',
  'confirmed': 'Đã xác nhận',
  'shipping': 'Đang giao hàng',
  'completed': 'Hoàn thành',
  'cancelled': 'Đã hủy'
};

const fetchChartData = async () => {
  isLoadingChart.value = true;
  try {
    const response = await axios.get('/admin/dashboard/chart-data?range=' + chartRange.value);
    const data = response.data;

    lineChartData.value = {
      labels: data.revenue.labels,
      datasets: [
        {
          label: 'Doanh thu',
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgb(54, 162, 235)',
          borderWidth: 2,
          pointBackgroundColor: 'rgb(54, 162, 235)',
          fill: true,
          tension: 0.3,
          data: data.revenue.data
        }
      ]
    };

    // Chuẩn bị màu cho doughnut chart
    const bgColors = data.orderStatus.labels.map(status => statusColors[status] || '#cccccc');
    const mappedLabels = data.orderStatus.labels.map(status => statusNames[status] || status);

    doughnutChartData.value = {
      labels: mappedLabels,
      datasets: [
        {
          backgroundColor: bgColors,
          borderWidth: 1,
          data: data.orderStatus.data
        }
      ]
    };

  } catch (error) {
    console.error("Lỗi tải dữ liệu biểu đồ", error);
  } finally {
    isLoadingChart.value = false;
  }
};

onMounted(() => {
  fetchChartData();
});

// Utils
const formatCurrency = (value) => {
  if (!value) return '0 đ';
  return new Intl.NumberFormat('vi-VN').format(value) + ' đ';
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const d = new Date(dateString);
  return d.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
};

const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'pending': return 'bg-secondary';
    case 'confirmed': return 'bg-info text-dark';
    case 'shipping': return 'bg-warning text-dark';
    case 'completed': return 'bg-success';
    case 'cancelled': return 'bg-danger';
    default: return 'bg-dark';
  }
};

const getStatusName = (status) => {
  return statusNames[status] || status;
};

const getProductImage = (product) => {
  if (product && product.images && product.images.length > 0) {
    return '/storage/' + product.images[0].image_path;
  }
  return null;
};
</script>
