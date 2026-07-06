<template>
  <Layout title="Quản lý Sản phẩm">

    <Head title="Sản phẩm" />
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-dark">Danh sách Sản phẩm</h5>
        <button class="btn btn-dark btn-sm rounded-pill px-3" @click="openCreateModal">
          <i class="fa-solid fa-plus me-1"></i> Thêm mới
        </button>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="border-0 px-4" width="5%">STT</th>
                <th class="border-0">Tên sản phẩm</th>
                <th class="border-0">Danh mục</th>
                <th class="border-0 text-end">Giá bán</th>
                <th class="border-0 text-center">Nổi bật</th>
                <th class="border-0 text-center">Trạng thái</th>
                <th class="border-0 text-center" width="15%">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(product, index) in products.data" :key="product.id">
                <td class="px-4 text-muted">{{ (products.current_page - 1) * products.per_page + index + 1 }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <img v-if="product.images && product.images.length > 0"
                      :src="'/storage/' + getPrimaryImage(product)" :alt="product.name"
                      class="rounded me-3 object-fit-cover" style="width: 48px; height: 48px; object-position: top;">
                    <div v-else
                      class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-muted"
                      style="width: 48px; height: 48px;">
                      <i class="fa-regular fa-image"></i>
                    </div>
                    <div>
                      <div class="fw-medium text-dark">{{ product.name }}</div>
                      <small class="text-muted">{{ product.colors?.length || 0 }} màu sắc, {{ countSizes(product) }}
                        sizes</small>
                    </div>
                  </div>
                </td>
                <td>{{ product.category ? product.category.name : '-- Trống --' }}</td>
                <td class="text-end fw-medium text-dark">{{ formatPrice(product.price) }}đ</td>
                <td class="text-center">
                  <i v-if="product.is_featured" class="fa-solid fa-star text-warning"></i>
                  <i v-else class="fa-regular fa-star text-muted"></i>
                </td>
                <td class="text-center">
                  <span v-if="product.status === 'active'"
                    class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Hoạt động</span>
                  <span v-else class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">Đang ẩn</span>
                </td>
                <td class="text-center">
                  <button class="btn btn-sm btn-light text-primary me-1" @click="openEditModal(product.id)" title="Sửa">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </button>
                  <button class="btn btn-sm btn-light text-danger" @click="deleteProduct(product.id)" title="Xóa">
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="products.data.length === 0">
                <td colspan="7" class="text-center py-5 text-muted">
                  <i class="fa-solid fa-box-open fs-1 mb-3 text-light"></i><br>
                  Chưa có sản phẩm nào.
                </td>
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

    <!-- Modal Form (To và có cuộn) -->
    <div class="modal fade" id="productModal" tabindex="-1" ref="modalRef" data-bs-backdrop="static">
      <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <form @submit.prevent="submitForm" class="modal-content border-0 shadow">
          <div class="modal-header border-bottom-0 bg-light">
            <h5 class="modal-title fw-bold">{{ isEditing ? 'Sửa Sản Phẩm' : 'Thêm Sản Phẩm Mới' }}</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body p-4 bg-light bg-opacity-50">
            <div class="row g-4">
              <!-- Cột Trái: Thông tin chung -->
              <div class="col-lg-5">
                <div class="card shadow-sm border-0 mb-4">
                  <div class="card-header bg-white">
                    <h6 class="m-0 fw-bold">Thông tin chung</h6>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                      <label class="form-label fw-medium">Tên sản phẩm <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.name }"
                        v-model="form.name" required placeholder="Ví dụ: Áo thun nam">
                      <div class="invalid-feedback" v-if="form.errors.name">{{ form.errors.name }}</div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-medium">Danh mục <span class="text-danger">*</span></label>
                      <select class="form-select" :class="{ 'is-invalid': form.errors.category_id }"
                        v-model="form.category_id" required>
                        <option value="">-- Chọn danh mục --</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                      </select>
                      <div class="invalid-feedback" v-if="form.errors.category_id">{{ form.errors.category_id }}</div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-medium">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.price }"
                        v-model="displayPrice" required placeholder="0">
                      <div class="invalid-feedback" v-if="form.errors.price">{{ form.errors.price }}</div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-medium">Mô tả chi tiết</label>
                      <textarea class="form-control" v-model="form.description" rows="4"
                        placeholder="Nhập mô tả sản phẩm..."></textarea>
                    </div>
                    <div class="row">
                      <div class="col-6 mb-3">
                        <label class="form-label fw-medium">Trạng thái</label>
                        <select class="form-select" v-model="form.status">
                          <option value="active">Hoạt động</option>
                          <option value="inactive">Đang ẩn</option>
                        </select>
                      </div>
                      <div class="col-6 mb-3">
                        <label class="form-label fw-medium invisible">Nổi bật</label>
                        <div class="form-check mt-1">
                          <input class="form-check-input" type="checkbox" id="is_featured" v-model="form.is_featured"
                            :true-value="1" :false-value="0">
                          <label class="form-check-label fw-medium" for="is_featured">Sản phẩm nổi bật</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Cột Phải: Biến thể (Màu sắc & Size & Ảnh) -->
              <div class="col-lg-7">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h6 class="m-0 fw-bold">Phân loại Màu sắc & Kích thước</h6>
                  <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" @click="addColor">
                    <i class="fa-solid fa-plus me-1"></i> Thêm Màu Sắc
                  </button>
                </div>

                <div v-for="(color, cIdx) in form.colors" :key="cIdx" class="card shadow-sm border-0 mb-3 color-block">
                  <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                    <div class="d-flex align-items-center gap-2">
                      <input type="color" class="form-control form-control-color p-1" style="width: 35px; height: 35px;"
                        v-model="color.code">
                      <input type="text" class="form-control form-control-sm fw-bold border-0 bg-light"
                        placeholder="Tên màu" v-model="color.name" required>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger border-0" @click="removeColor(cIdx)"><i
                        class="fa-solid fa-xmark"></i></button>
                  </div>
                  <div class="card-body p-3">
                    <div class="row">
                      <!-- Cột Ảnh -->
                      <div class="col-md-5 border-end pe-3">
                        <label class="form-label small text-muted fw-medium mb-1"><i
                            class="fa-regular fa-image me-1"></i> Ảnh màu này</label>
                        <input type="file" class="form-control form-control-sm mb-2" multiple accept="image/*"
                          @change="handleFileUpload($event, cIdx)" :key="'file-' + fileInputKey">
                        <div class="d-flex flex-wrap gap-2 mt-2">
                          <!-- Existing Images (from server) -->
                          <div v-for="(path, eIdx) in color.existing_images" :key="'e' + eIdx"
                            class="position-relative existing-image">
                            <img :src="'/storage/' + path" class="rounded border object-fit-cover"
                              style="width: 45px; height: 45px; object-position: top;">
                            <div class="position-absolute top-0 start-0" style="padding: 2px;">
                              <input type="radio" name="primary_image" class="form-check-input m-0"
                                :value="`existing_${cIdx}_${eIdx}`" v-model="form.primary_image_key"
                                title="Chọn làm ảnh chính" style="cursor: pointer;">
                            </div>
                            <button type="button" class="btn btn-danger p-0 position-absolute top-0 end-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 14px; height: 14px; transform: translate(30%, -30%);" @click="removeExistingImage(cIdx, eIdx)">
                              <i class="fa-solid fa-xmark" style="font-size: 9px;"></i>
                            </button>
                          </div>
                          <!-- New Images (preview) -->
                          <div v-for="(preview, pIdx) in color.preview_images" :key="'p' + pIdx"
                            class="position-relative">
                            <img :src="preview" class="rounded border object-fit-cover"
                              style="width: 45px; height: 45px; object-position: top;">
                            <div class="position-absolute top-0 start-0" style="padding: 2px;">
                              <input type="radio" name="primary_image" class="form-check-input m-0"
                                :value="`new_${cIdx}_${pIdx}`" v-model="form.primary_image_key"
                                title="Chọn làm ảnh chính" style="cursor: pointer;">
                            </div>
                            <button type="button" class="btn btn-danger p-0 position-absolute top-0 end-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 14px; height: 14px; transform: translate(30%, -30%);" @click="removePreviewImage(cIdx, pIdx)">
                              <i class="fa-solid fa-xmark" style="font-size: 9px;"></i>
                            </button>
                          </div>
                        </div>
                      </div>

                      <!-- Cột Size -->
                      <div class="col-md-7 ps-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                          <label class="form-label small text-muted fw-medium mb-0"><i
                              class="fa-solid fa-ruler me-1"></i> Kích thước & Tồn kho</label>
                          <button type="button" class="btn btn-sm btn-light text-primary py-0 px-2"
                            @click="addSize(cIdx)"><i class="fa-solid fa-plus"></i> Size</button>
                        </div>
                        <div class="sizes-container">
                          <div v-for="(size, sIdx) in color.sizes" :key="sIdx" class="d-flex gap-2 mb-2 size-row">
                            <input type="text" class="form-control form-control-sm" placeholder="Tên Size"
                              v-model="size.name" required>
                            <input type="number" class="form-control form-control-sm" placeholder="Tồn kho"
                              v-model="size.stock" min="0" required>
                            <button type="button" class="btn btn-sm btn-light text-danger"
                              @click="removeSize(cIdx, sIdx)"><i class="fa-solid fa-trash-can"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-if="form.colors.length === 0" class="text-danger small mt-2">
                  Vui lòng thêm ít nhất 1 màu sắc!
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer border-top-0 bg-light">
            <button type="button" class="btn btn-light border px-4" @click="closeModal">Hủy</button>
            <button type="submit" class="btn btn-dark px-4" :disabled="form.processing || form.colors.length === 0">
              <i v-if="form.processing" class="fa-solid fa-spinner fa-spin me-2"></i>Lưu Sản Phẩm
            </button>
          </div>
        </form>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import Layout from './Layout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';

const props = defineProps({
  products: Object,
  categories: Array,
});

const modalRef = ref(null);
let bootstrapModal = null;
const isEditing = ref(false);
const editingId = ref(null);
const fileInputKey = ref(0);

const form = useForm({
  name: '',
  category_id: '',
  price: 0,
  description: '',
  status: 'active',
  is_featured: 0,
  colors: [],
  primary_image_key: null,
  _method: 'POST'
});

onMounted(() => {
  bootstrapModal = new bootstrap.Modal(modalRef.value);
});

const formatPrice = (value) => {
  return new Intl.NumberFormat('vi-VN').format(value);
};

const displayPrice = computed({
  get() {
    return form.price ? formatPrice(form.price) : '';
  },
  set(value) {
    const numericValue = value.toString().replace(/[^0-9]/g, '');
    form.price = numericValue ? parseInt(numericValue, 10) : 0;
  }
});

const countSizes = (product) => {
  if (!product.colors) return 0;
  return product.colors.reduce((total, color) => {
    return total + (color.sizes ? color.sizes.length : 0);
  }, 0);
};

const getPrimaryImage = (product) => {
  if (!product.images || product.images.length === 0) return '';
  const primary = product.images.find(img => img.is_primary);
  return primary ? primary.image_path : product.images[0].image_path;
};

const openCreateModal = () => {
  isEditing.value = false;
  editingId.value = null;
  fileInputKey.value++;
  form.reset();
  form.clearErrors();
  
  form.name = '';
  form.category_id = '';
  form.price = 0;
  form.description = '';
  form.status = 'active';
  form.is_featured = 0;
  form.primary_image_key = null;
  form._method = 'POST';

  form.colors = [];
  addColor(); // Add default color

  bootstrapModal.show();
};

const openEditModal = async (id) => {
  isEditing.value = true;
  editingId.value = id;
  fileInputKey.value++;
  form.clearErrors();
  form._method = 'PUT'; // Inertia needs _method for File uploads on PUT requests

  try {
    const response = await fetch(`/admin/products/${id}`, {
      headers: {
        'Accept': 'application/json'
      }
    });
    const data = await response.json();

    form.name = data.name;
    form.category_id = data.category_id;
    form.price = data.price;
    form.description = data.description || '';
    form.status = data.status;
    form.is_featured = data.is_featured;

    let primaryKeyFound = null;
    form.colors = [];
    if (data.colors && data.colors.length > 0) {
      data.colors.forEach((color, cIdx) => {
        let existingImages = [];
        if (color.images && color.images.length > 0) {
          color.images.forEach((img, eIdx) => {
            existingImages.push(img.image_path);
            if (img.is_primary) {
              primaryKeyFound = `existing_${cIdx}_${eIdx}`;
            }
          });
        }

        let sizes = [];
        if (color.sizes && color.sizes.length > 0) {
          sizes = color.sizes.map(s => ({ name: s.size_name, stock: s.stock }));
        } else {
          sizes = [{ name: '', stock: '' }];
        }

        form.colors.push({
          name: color.color_name,
          code: color.color_code,
          existing_images: existingImages,
          images: [],
          preview_images: [],
          sizes: sizes
        });
      });
    } else {
      addColor();
    }

    form.primary_image_key = primaryKeyFound;

    bootstrapModal.show();
  } catch (error) {
    window.showToast('Không lấy được thông tin sản phẩm', 'error');
  }
};

const closeModal = () => {
  bootstrapModal.hide();
};

const addColor = () => {
  form.colors.push({
    name: '',
    code: '#000000',
    images: [],
    existing_images: [],
    preview_images: [],
    sizes: [
      { name: '', stock: '' }
    ]
  });
};

const removeColor = (cIdx) => {
  form.colors.splice(cIdx, 1);
};

const addSize = (cIdx) => {
  form.colors[cIdx].sizes.push({ name: '', stock: '' });
};

const removeSize = (cIdx, sIdx) => {
  form.colors[cIdx].sizes.splice(sIdx, 1);
};

const removeExistingImage = (cIdx, eIdx) => {
  const removedKey = `existing_${cIdx}_${eIdx}`;
  form.colors[cIdx].existing_images.splice(eIdx, 1);
  if (form.primary_image_key === removedKey) {
    form.primary_image_key = null;
  }
};

const removePreviewImage = (cIdx, pIdx) => {
  const removedKey = `new_${cIdx}_${pIdx}`;
  form.colors[cIdx].preview_images.splice(pIdx, 1);
  form.colors[cIdx].images.splice(pIdx, 1);
  if (form.primary_image_key === removedKey) {
    form.primary_image_key = null;
  }
};

const handleFileUpload = (event, cIdx) => {
  const files = event.target.files;
  const color = form.colors[cIdx];

  if (files.length > 0) {
    const startIdx = color.preview_images.length;
    Array.from(files).forEach((file, pIdx) => {
      color.images.push(file);
      color.preview_images.push(URL.createObjectURL(file));
      if (!form.primary_image_key) {
        form.primary_image_key = `new_${cIdx}_${startIdx + pIdx}`;
      }
    });
  }
};

const submitForm = () => {
  if (form.colors.length === 0) return;

  // Validation client-side for sizes
  let hasError = false;
  for (const color of form.colors) {
    if (color.sizes.length === 0) {
      Swal.fire('Lỗi', `Màu "${color.name}" phải có ít nhất 1 kích thước.`, 'error');
      hasError = true;
      break;
    }
  }
  if (hasError) return;

  const url = isEditing.value ? `/admin/products/${editingId.value}` : '/admin/products';

  form.post(url, {
    forceFormData: true,
    onSuccess: () => {
      closeModal();
      window.showToast(isEditing.value ? 'Cập nhật sản phẩm thành công!' : 'Thêm sản phẩm thành công!');
    },
    onError: (errors) => {
      if (Object.keys(errors).length === 0) {
        window.showToast('Đã có lỗi xảy ra', 'error');
      }
    }
  });
};

const deleteProduct = (id) => {
  Swal.fire({
    title: 'Bạn có chắc chắn?',
    text: "Sản phẩm và toàn bộ hình ảnh, size của nó sẽ bị xóa vĩnh viễn!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Đồng ý, Xóa!',
    cancelButtonText: 'Hủy'
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(`/admin/products/${id}`, {
        onSuccess: () => window.showToast('Xóa sản phẩm thành công!')
      });
    }
  });
};
</script>
