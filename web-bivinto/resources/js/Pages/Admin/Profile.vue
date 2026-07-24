<template>
  <Layout title="Hồ sơ cá nhân">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-4">
        <h6 class="card-title fw-semibold mb-3">Cập nhật hồ sơ</h6>

        <form @submit.prevent="submitProfile">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Họ và tên</label>
              <input type="text" class="form-control" v-model="form.name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Số điện thoại</label>
              <input type="text" class="form-control" v-model="form.phone">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" v-model="form.email" required>
          </div>

          <hr class="my-4">
          <h6 class="card-title fw-semibold mb-3">Đổi mật khẩu <span class="text-muted small fw-normal"></span></h6>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Mật khẩu mới</label>
              <div class="position-relative">
                <input :type="showPassword ? 'text' : 'password'" class="form-control" v-model="form.password" @focus="passwordFocused = true" @blur="passwordFocused = false">
                <i v-show="passwordFocused || form.password.length > 0" :class="showPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" @mousedown.prevent @click="showPassword = !showPassword" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #616161;"></i>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Xác nhận mật khẩu</label>
              <div class="position-relative">
                <input :type="showPassword ? 'text' : 'password'" class="form-control" v-model="form.password_confirmation" @focus="passwordConfirmFocused = true" @blur="passwordConfirmFocused = false">
                <i v-show="passwordConfirmFocused || form.password_confirmation.length > 0" :class="showPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" @mousedown.prevent @click="showPassword = !showPassword" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #616161;"></i>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary px-4" :disabled="form.processing">
              <i class="fa-solid fa-save me-2"></i> Lưu thay đổi
            </button>
          </div>
        </form>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Layout from './Layout.vue';

const showPassword = ref(false);
const passwordFocused = ref(false);
const passwordConfirmFocused = ref(false);

const page = usePage();
const user = page.props.auth.user;

const form = useForm({
  name: user?.name || '',
  email: user?.email || '',
  phone: user?.phone == '0' || user?.phone == 0 ? '' : (user?.phone || ''),
  password: '',
  password_confirmation: ''
});

const submitProfile = () => {
  form.put('/admin/profile', {
    onSuccess: () => {
      window.showToast('Cập nhật hồ sơ thành công!');
      form.password = '';
      form.password_confirmation = '';
    },
    onError: (errors) => {
      // Show first error message if any
      const firstError = Object.values(errors)[0];
      if (firstError) {
        window.showToast(firstError, 'error');
      }
    }
  });
};
</script>
