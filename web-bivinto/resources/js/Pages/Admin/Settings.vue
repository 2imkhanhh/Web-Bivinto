<template>
  <Layout title="Cài đặt chung">
    <Head title="Cài đặt chung" />

    <div class="settings-page">
      <!-- Tabs Navigation -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
          <ul class="nav nav-tabs border-0 px-3 pt-3">
            <li class="nav-item" v-for="(group, groupKey) in settings" :key="groupKey">
              <a class="nav-link" :class="{ active: activeTab === groupKey }" href="#"
                @click.prevent="activeTab = groupKey">
                <i :class="tabIcons[groupKey]" class="me-2"></i>
                {{ group.label }}
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!-- Settings Form -->
      <form @submit.prevent="saveSettings" enctype="multipart/form-data">
        <div v-for="(group, groupKey) in settings" :key="groupKey" v-show="activeTab === groupKey">
          <!-- Loop through sections within each tab -->
          <div v-for="(section, sIdx) in group.sections" :key="section.key"
            class="card shadow-sm border-0" :class="{ 'mb-4': sIdx < group.sections.length - 1 }">
            <div class="card-header bg-white py-3">
              <h5 class="m-0 fw-bold text-dark">{{ section.label }}</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div v-for="item in section.items" :key="item.key"
                  :class="item.type === 'image' ? 'col-md-6 mb-4' : 'col-md-6 mb-3'">

                  <!-- Text Input -->
                  <div v-if="item.type === 'text'">
                    <label class="form-label fw-medium">{{ item.label }}</label>
                    <input type="text" class="form-control" v-model="formData[item.key]">
                  </div>

                  <!-- Textarea Input -->
                  <div v-else-if="item.type === 'textarea'">
                    <label class="form-label fw-medium">{{ item.label }}</label>
                    <textarea class="form-control" rows="3" v-model="formData[item.key]"></textarea>
                  </div>

                  <!-- Image Input -->
                  <div v-else-if="item.type === 'image'">
                    <label class="form-label fw-medium">{{ item.label }}</label>
                    <div class="d-flex align-items-start gap-3">
                      <div class="settings-img-preview border rounded overflow-hidden flex-shrink-0"
                        style="width: 160px; height: 100px;">
                        <img :src="previewUrls[item.key] || item.display_url || '/images/placeholder.png'"
                          alt="Preview" class="w-100 h-100 object-fit-cover">
                      </div>
                      <div class="flex-grow-1">
                        <input type="file" class="form-control" accept="image/*"
                          @change="handleFileChange($event, item.key)">
                        <small class="text-muted d-block mt-1">Tối đa 2MB. Định dạng: JPEG, PNG, GIF</small>
                      </div>
                    </div>
                  </div>
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
  settings: Object,
});

const activeTab = ref('homepage');
const saving = ref(false);
const formData = reactive({});
const fileInputs = reactive({});
const previewUrls = reactive({});

const tabIcons = {
  homepage: 'fa-solid fa-house',
  aboutpage: 'fa-solid fa-circle-info',
  collabpage: 'fa-solid fa-handshake',
  policypage: 'fa-solid fa-shield-halved',
  footer: 'fa-solid fa-table-columns',
};

// Initialize form data from props
onMounted(() => {
  for (const groupKey in props.settings) {
    const group = props.settings[groupKey];
    for (const section of group.sections) {
      for (const item of section.items) {
        if (item.type !== 'image') {
          formData[item.key] = item.value || '';
        }
      }
    }
  }
});

const handleFileChange = (event, key) => {
  const file = event.target.files[0];
  if (file) {
    fileInputs[key] = file;
    // Preview
    const reader = new FileReader();
    reader.onload = (e) => {
      previewUrls[key] = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const saveSettings = () => {
  saving.value = true;

  const data = new FormData();
  data.append('_method', 'PUT');

  // Add text/textarea data
  for (const key in formData) {
    data.append(key, formData[key]);
  }

  // Add file data
  for (const key in fileInputs) {
    data.append(key, fileInputs[key]);
  }

  router.post('/admin/settings', data, {
    forceFormData: true,
    onSuccess: () => {
      saving.value = false;
      window.showToast('Cài đặt đã được cập nhật thành công!');
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
.nav-tabs .nav-link {
  color: #6c757d;
  border: none;
  border-bottom: 2px solid transparent;
  font-weight: 500;
  padding: 0.75rem 1.25rem;
  transition: all 0.2s;
}

.nav-tabs .nav-link:hover {
  color: #0d6efd;
  border-bottom-color: #0d6efd;
  background-color: #f8f9fa;
}

.nav-tabs .nav-link.active {
  color: #0d6efd;
  border-bottom-color: #0d6efd;
  background: none;
}

.settings-img-preview {
  background: #f8f9fa;
}
</style>
