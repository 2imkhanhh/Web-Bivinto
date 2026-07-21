<template>
  <Layout title="Cấu hình Email">
    <Head title="Cấu hình Email" />

    <div class="settings-page">
      <!-- Settings Form -->
      <form @submit.prevent="saveSettings">
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="m-0 fw-bold text-dark">Thiết lập cấu hình SMTP</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div v-for="item in settings" :key="item.key" class="col-md-6 mb-3">
                
                <div v-if="item.type === 'password'">
                  <label class="form-label fw-medium">{{ item.label }}</label>
                  <input type="password" class="form-control" v-model="formData[item.key]" placeholder="Bỏ trống nếu không muốn đổi">
                </div>
                
                <div v-else>
                  <label class="form-label fw-medium">{{ item.label }} <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" v-model="formData[item.key]" required>
                </div>

              </div>
            </div>
          </div>
        </div>

        <!-- Save Button -->
        <div class="mt-4 d-flex justify-content-end">
          <button type="submit" class="btn btn-dark rounded-pill px-5 py-2 fw-medium" :disabled="saving">
            <i v-if="saving" class="fa-solid fa-spinner fa-spin me-2"></i>
            <i v-else class="fa-solid fa-floppy-disk me-2"></i>
            Lưu cài đặt
          </button>
        </div>
      </form>
    </div>
  </Layout>
</template>

<script setup>
import Layout from './Layout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, reactive, onMounted } from 'vue';

const props = defineProps({
  settings: Array,
});

const saving = ref(false);
const formData = reactive({});

// Initialize form data from props
onMounted(() => {
  for (const item of props.settings) {
    if (item.type !== 'password') {
      formData[item.key] = item.value || '';
    } else {
      formData[item.key] = ''; // Để rỗng cho trường mật khẩu ban đầu
    }
  }
});

const saveSettings = () => {
  saving.value = true;

  router.put('/admin/mail-settings', formData, {
    onSuccess: () => {
      saving.value = false;
      window.showToast('Cấu hình Email đã được cập nhật thành công!');
      // Clear password field sau khi save
      for (const item of props.settings) {
        if (item.type === 'password') {
          formData[item.key] = '';
        }
      }
    },
    onError: (errors) => {
      saving.value = false;
      window.showToast('Có lỗi xảy ra, vui lòng thử lại.', 'error');
    },
    onFinish: () => {
      saving.value = false;
    }
  });
};
</script>

<style scoped>
.settings-page {
  max-width: 900px;
}
</style>
