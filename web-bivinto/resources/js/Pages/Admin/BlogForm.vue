<template>
  <Layout :title="isEditing ? 'Sửa bài viết' : 'Thêm bài viết'">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">{{ isEditing ? 'Sửa bài viết' : 'Thêm bài viết' }}</h4>
      <Link href="/admin/blogs" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
      </Link>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body p-4">
            <div class="mb-3">
              <label class="form-label fw-medium">Tiêu đề <span class="text-danger">*</span></label>
              <input type="text" class="form-control" v-model="form.title" placeholder="Nhập tiêu đề bài viết" required>
              <div v-if="form.errors.title" class="text-danger small mt-1">{{ form.errors.title }}</div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-medium">Mô tả ngắn (Excerpt)</label>
              <textarea class="form-control" v-model="form.excerpt" rows="3"
                placeholder="Nhập mô tả ngắn gọn..."></textarea>
              <div v-if="form.errors.excerpt" class="text-danger small mt-1">{{ form.errors.excerpt }}</div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-medium">Nội dung <span class="text-danger">*</span></label>
              <div class="quill-container" style="min-height: 400px; padding-bottom: 50px;">
                <QuillEditor ref="quillEditor" v-model:content="form.content" contentType="html"
                  :options="editorOptions" />
              </div>
              <div v-if="form.errors.content" class="text-danger small mt-1">{{ form.errors.content }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body p-4">
            <div class="mb-4">
              <label class="form-label fw-medium">Ảnh bìa</label>

              <!-- Preview -->
              <div class="mb-3">
                <img v-if="previewImage" :src="previewImage" class="img-fluid rounded border w-100 object-fit-cover"
                  style="height: 200px;" />
                <div v-else
                  class="bg-light rounded border d-flex align-items-center justify-content-center text-muted w-100"
                  style="height: 200px;">
                  <div class="text-center">
                    <i class="fa-regular fa-image fs-1 mb-2"></i>
                    <p class="mb-0 small">Chưa có ảnh</p>
                  </div>
                </div>
              </div>

              <input type="file" class="form-control" @change="handleImageUpload" accept="image/*">
              <div v-if="form.errors.image" class="text-danger small mt-1">{{ form.errors.image }}</div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-medium">Trạng thái <span class="text-danger">*</span></label>
              <select v-model="form.status" class="form-select" :class="{ 'is-invalid': form.errors.status }">
                <option value="Draft">Bản nháp</option>
                <option value="Published">Xuất bản</option>
              </select>
              <div v-if="form.errors.status" class="invalid-feedback">{{ form.errors.status }}</div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-medium">Vị trí hiển thị nổi bật</label>
              <input type="number" v-model="form.position" class="form-control" placeholder="1, 2, 3..." min="0"
                :class="{ 'is-invalid': form.errors.position }">
              <div v-if="form.errors.position" class="invalid-feedback">{{ form.errors.position }}</div>
            </div>

            <div class="d-grid mt-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="isFeatured" v-model="form.is_featured">
                <label class="form-check-label" for="isFeatured">Bài viết nổi bật</label>
              </div>
            </div>

            <button type="button" @click="submit" class="btn btn-dark w-100 py-2 rounded-pill mt-3"
              :disabled="form.processing">
              <span v-if="form.processing" class="spinner-border spinner-border-sm me-2" role="status"
                aria-hidden="true"></span>
              {{ isEditing ? 'Cập nhật bài viết' : 'Lưu bài viết' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import Layout from './Layout.vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import axios from 'axios';

const props = defineProps({
  blog: {
    type: Object,
    default: null
  }
});

const isEditing = computed(() => !!props.blog);
const quillEditor = ref(null);

const editorOptions = {
  theme: 'snow',
  modules: {
    toolbar: {
      container: [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        [{ 'script': 'sub' }, { 'script': 'super' }],
        [{ 'indent': '-1' }, { 'indent': '+1' }],
        [{ 'direction': 'rtl' }],
        [{ 'size': ['small', false, 'large', 'huge'] }],
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'font': [] }],
        [{ 'align': [] }],
        ['clean'],
        ['link', 'image', 'video']
      ],
      handlers: {
        image: function () {
          const input = document.createElement('input');
          input.setAttribute('type', 'file');
          input.setAttribute('accept', 'image/*');
          input.click();

          input.onchange = async () => {
            const file = input.files[0];
            const formData = new FormData();
            formData.append('image', file);

            try {
              const res = await axios.post('/admin/upload-image', formData, {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              });

              const url = res.data.url;
              const quill = quillEditor.value.getQuill();
              const range = quill.getSelection(true);
              quill.insertEmbed(range.index, 'image', url);
              quill.setSelection(range.index + 1);
            } catch (error) {
              console.error('Error uploading image:', error);
              alert('Tải ảnh lên thất bại!');
            }
          };
        }
      }
    }
  }
};

const form = useForm({
  title: props.blog?.title || '',
  excerpt: props.blog?.excerpt || '',
  content: props.blog?.content || '',
  image: null,
  is_featured: props.blog?.is_featured == 1,
  position: props.blog?.position || 0,
  status: (props.blog?.status === 'published' || props.blog?.status === 'Published') ? 'Published' :
    (props.blog?.status === 'draft' || props.blog?.status === 'Draft' ? 'Draft' : 'Published'),
  _method: isEditing.value ? 'PUT' : 'POST',
});

const previewImage = ref(props.blog?.image_path ? `/storage/${props.blog.image_path}` : null);

const handleImageUpload = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.image = file;
    previewImage.value = URL.createObjectURL(file);
  } else {
    form.image = null;
    previewImage.value = props.blog?.image_path ? `/storage/${props.blog.image_path}` : null;
  }
};

const submit = () => {
  if (isEditing.value) {
    form.post(`/admin/blogs/${props.blog.id}`, {
      preserveScroll: true
    });
  } else {
    form.post('/admin/blogs', {
      preserveScroll: true
    });
  }
};
</script>

<style>
/* Adjust Quill editor height to prevent it from overlapping */
.ql-container {
  height: 350px !important;
  font-family: inherit !important;
  font-size: 1rem !important;
}

.ql-editor {
  height: 100%;
}
</style>
