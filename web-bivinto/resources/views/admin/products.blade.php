@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')
@section('page-title', 'Quản lý Sản phẩm')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-dark">Danh sách Sản phẩm</h5>
            <button class="btn btn-dark btn-sm rounded-pill px-3" onclick="openCreateModal()">
                <i class="fa-solid fa-plus me-1"></i> Thêm mới
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 px-4" width="5%">ID</th>
                            <th class="border-0">Tên sản phẩm</th>
                            <th class="border-0">Danh mục</th>
                            <th class="border-0 text-end">Giá bán</th>
                            <th class="border-0 text-center">Trạng thái</th>
                            <th class="border-0 text-center" width="15%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr id="row-{{ $product->id }}">
                                <td class="px-4 text-muted">{{ $product->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                                 alt="{{ $product->name }}" class="rounded me-3 object-fit-cover" 
                                                 style="width: 48px; height: 48px;">
                                        @else
                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-muted" 
                                                 style="width: 48px; height: 48px;">
                                                <i class="fa-regular fa-image"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-medium text-dark">{{ $product->name }}</div>
                                            <small class="text-muted">{{ $product->colors->count() }} màu sắc, {{ $product->colors->sum(fn($c) => $c->sizes->count()) }} sizes</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->category ? $product->category->name : '-- Trống --' }}</td>
                                <td class="text-end fw-bold text-danger">{{ number_format($product->price, 0, ',', '.') }}đ</td>
                                <td class="text-center">
                                    @if ($product->status === 'active')
                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Hoạt động</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">Đang ẩn</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light text-primary me-1" onclick="openEditModal({{ $product->id }})" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-danger" onclick="deleteProduct({{ $product->id }})" title="Xóa">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-box-open fs-1 mb-3 text-light"></i><br>
                                    Chưa có sản phẩm nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form (To và có cuộn) -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
            <form id="productForm" onsubmit="submitForm(event)" class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom-0 bg-light">
                        <h5 class="modal-title fw-bold" id="productModalLabel">Thêm Sản Phẩm Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 bg-light bg-opacity-50">
                        <input type="hidden" id="product_id">

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
                                            <input type="text" class="form-control" id="name" required placeholder="Ví dụ: Áo thun nam">
                                            <div class="invalid-feedback" id="error-name"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Danh mục <span class="text-danger">*</span></label>
                                            <select class="form-select" id="category_id" required>
                                                <option value="">-- Chọn danh mục --</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="error-category_id"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="price" required min="0" value="0">
                                            <div class="invalid-feedback" id="error-price"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Mô tả chi tiết</label>
                                            <textarea class="form-control" id="description" rows="4" placeholder="Nhập mô tả sản phẩm..."></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label class="form-label fw-medium">Trạng thái</label>
                                                <select class="form-select" id="status">
                                                    <option value="active">Hoạt động</option>
                                                    <option value="inactive">Đang ẩn</option>
                                                </select>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <label class="form-label fw-medium">Sản phẩm nổi bật</label>
                                                <div class="form-check form-switch mt-1">
                                                    <input class="form-check-input" type="checkbox" id="is_featured" style="transform: scale(1.3); margin-left: -2em; cursor: pointer;">
                                                    <label class="form-check-label ms-2" for="is_featured">Có</label>
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
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="addColor()">
                                        <i class="fa-solid fa-plus me-1"></i> Thêm Màu Sắc
                                    </button>
                                </div>

                                <div id="colorsContainer">
                                    <!-- Các khối màu sẽ được render ở đây -->
                                </div>
                                <div class="invalid-feedback d-block" id="error-colors"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 bg-light">
                        <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-dark px-4" id="btnSubmit">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="spinner"></span>Lưu Sản Phẩm
                        </button>
                    </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const productModal = new bootstrap.Modal(document.getElementById('productModal'));
        let isEditMode = false;
        let colorIndex = 0;

        // --- Logic Xử lý Form Động ---
        
        function addColor(colorData = null) {
            const idx = colorIndex++;
            const colorName = colorData ? colorData.color_name : '';
            const colorCode = colorData ? colorData.color_code : '#000000';
            
            const html = `
                <div class="card shadow-sm border-0 mb-3 color-block" id="color-block-${idx}" data-index="${idx}">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" class="form-control form-control-color p-1" style="width: 35px; height: 35px;" id="color-code-${idx}" value="${colorCode}">
                            <input type="text" class="form-control form-control-sm fw-bold border-0 bg-light" id="color-name-${idx}" placeholder="Tên màu (VD: Đen)" value="${colorName}" required>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeColor(${idx})"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <!-- Cột Ảnh -->
                            <div class="col-md-5 border-end pe-3">
                                <label class="form-label small text-muted fw-medium mb-1"><i class="fa-regular fa-image me-1"></i> Ảnh màu này</label>
                                <input type="file" class="form-control form-control-sm mb-2" id="color-images-${idx}" multiple accept="image/*">
                                <div id="preview-images-${idx}" class="d-flex flex-wrap gap-2 mt-2">
                                    <!-- Preview ảnh sẽ hiện ở đây -->
                                </div>
                            </div>
                            
                            <!-- Cột Size -->
                            <div class="col-md-7 ps-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label small text-muted fw-medium mb-0"><i class="fa-solid fa-ruler me-1"></i> Kích thước & Tồn kho</label>
                                    <button type="button" class="btn btn-sm btn-light text-primary py-0 px-2" onclick="addSize(${idx})"><i class="fa-solid fa-plus"></i> Size</button>
                                </div>
                                <div id="sizes-container-${idx}" class="sizes-container">
                                    <!-- Các ô nhập size hiện ở đây -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('colorsContainer').insertAdjacentHTML('beforeend', html);
            
            // Xử lý preview ảnh khi chọn file
            document.getElementById(`color-images-${idx}`).addEventListener('change', function(e) {
                previewImages(this, idx);
            });

            // Nếu là edit thì render sẵn các size và ảnh cũ
            if (colorData) {
                if (colorData.sizes && colorData.sizes.length > 0) {
                    colorData.sizes.forEach(size => addSize(idx, size));
                } else {
                    addSize(idx);
                }
                
                // Hiển thị ảnh cũ
                if (colorData.images && colorData.images.length > 0) {
                    const previewContainer = document.getElementById(`preview-images-${idx}`);
                    colorData.images.forEach(img => {
                        // Thêm class 'existing-image' để lúc submit giữ lại
                        previewContainer.insertAdjacentHTML('beforeend', `
                            <div class="position-relative existing-image" data-path="${img.image_path}">
                                <img src="/storage/${img.image_path}" class="rounded border object-fit-cover" style="width: 45px; height: 45px;">
                            </div>
                        `);
                    });
                }
            } else {
                // Thêm 1 size mặc định khi thêm màu mới
                addSize(idx);
            }
        }

        function removeColor(idx) {
            document.getElementById(`color-block-${idx}`).remove();
        }

        function addSize(colorIdx, sizeData = null) {
            const sizeName = sizeData ? sizeData.size_name : '';
            const sizeStock = sizeData ? sizeData.stock : 0;
            
            const html = `
                <div class="d-flex gap-2 mb-2 size-row">
                    <input type="text" class="form-control form-control-sm size-name" placeholder="Tên Size (M, L...)" value="${sizeName}" required>
                    <input type="number" class="form-control form-control-sm size-stock" placeholder="Tồn kho" value="${sizeStock}" min="0" required>
                    <button type="button" class="btn btn-sm btn-light text-danger" onclick="this.parentElement.remove()"><i class="fa-solid fa-trash-can"></i></button>
                </div>
            `;
            document.getElementById(`sizes-container-${colorIdx}`).insertAdjacentHTML('beforeend', html);
        }

        function previewImages(input, colorIdx) {
            const previewContainer = document.getElementById(`preview-images-${colorIdx}`);
            // Giữ lại các ảnh cũ (nếu đang edit)
            const existingImages = Array.from(previewContainer.querySelectorAll('.existing-image'));
            previewContainer.innerHTML = '';
            existingImages.forEach(el => previewContainer.appendChild(el));
            
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.insertAdjacentHTML('beforeend', `
                            <div class="position-relative">
                                <img src="${e.target.result}" class="rounded border object-fit-cover" style="width: 45px; height: 45px;">
                            </div>
                        `);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        // --- Logic Giao tiếp API ---

        function openCreateModal() {
            isEditMode = false;
            document.getElementById('productForm').reset();
            document.getElementById('product_id').value = '';
            document.getElementById('productModalLabel').innerText = 'Thêm Sản Phẩm Mới';
            
            // Xóa form động
            document.getElementById('colorsContainer').innerHTML = '';
            colorIndex = 0;
            addColor(); // Thêm sẵn 1 màu
            
            clearErrors();
            productModal.show();
        }

        async function openEditModal(id) {
            isEditMode = true;
            document.getElementById('product_id').value = id;
            document.getElementById('productModalLabel').innerText = 'Sửa Sản Phẩm';
            clearErrors();
            
            // Hiện loading (bạn có thể tự thêm UI loading)
            
            try {
                const response = await fetch(`/admin/products/${id}`);
                const data = await response.json();
                
                document.getElementById('name').value = data.name;
                document.getElementById('category_id').value = data.category_id;
                document.getElementById('price').value = data.price;
                document.getElementById('description').value = data.description || '';
                document.getElementById('status').value = data.status;
                document.getElementById('is_featured').checked = data.is_featured;
                
                // Load form động
                document.getElementById('colorsContainer').innerHTML = '';
                colorIndex = 0;
                
                if (data.colors && data.colors.length > 0) {
                    data.colors.forEach(color => addColor(color));
                } else {
                    addColor();
                }
                
                productModal.show();
            } catch (error) {
                showToast('Không lấy được thông tin sản phẩm', 'error');
            }
        }

        async function submitForm(e) {
            e.preventDefault();
            const btnSubmit = document.getElementById('btnSubmit');
            const spinner = document.getElementById('spinner');
            
            // Chuẩn bị FormData do có chứa File upload
            const formData = new FormData();
            formData.append('name', document.getElementById('name').value);
            formData.append('category_id', document.getElementById('category_id').value);
            formData.append('price', document.getElementById('price').value);
            formData.append('description', document.getElementById('description').value);
            formData.append('status', document.getElementById('status').value);
            formData.append('is_featured', document.getElementById('is_featured').checked ? 1 : 0);
            
            if (isEditMode) {
                formData.append('_method', 'PUT'); // Laravel requirement for PUT requests with FormData
            }

            // Gói dữ liệu Form động
            let hasError = false;
            const colorBlocks = document.querySelectorAll('.color-block');
            
            if (colorBlocks.length === 0) {
                document.getElementById('error-colors').innerText = "Vui lòng thêm ít nhất 1 màu sắc!";
                return;
            }

            colorBlocks.forEach((block, idx) => {
                const blockIndex = block.dataset.index;
                const cName = document.getElementById(`color-name-${blockIndex}`).value;
                const cCode = document.getElementById(`color-code-${blockIndex}`).value;
                
                formData.append(`colors[${idx}][name]`, cName);
                formData.append(`colors[${idx}][code]`, cCode);

                // Lấy các size của màu này
                const sizeRows = block.querySelectorAll('.size-row');
                if (sizeRows.length === 0) {
                    hasError = true;
                    Swal.fire('Lỗi', `Màu "${cName}" phải có ít nhất 1 kích thước.`, 'error');
                }
                
                sizeRows.forEach((row, sIdx) => {
                    const sName = row.querySelector('.size-name').value;
                    const sStock = row.querySelector('.size-stock').value;
                    formData.append(`colors[${idx}][sizes][${sIdx}][name]`, sName);
                    formData.append(`colors[${idx}][sizes][${sIdx}][stock]`, sStock);
                });

                // Lấy các file ảnh mới upload
                const fileInput = document.getElementById(`color-images-${blockIndex}`);
                if (fileInput.files.length > 0) {
                    Array.from(fileInput.files).forEach((file, fIdx) => {
                        formData.append(`colors[${idx}][images][]`, file);
                    });
                }
                
                // Lấy các ảnh cũ giữ lại (nếu đang Edit)
                const existingImages = block.querySelectorAll('.existing-image');
                existingImages.forEach((imgEl, eIdx) => {
                    formData.append(`colors[${idx}][existing_images][]`, imgEl.dataset.path);
                });
            });

            if (hasError) return;

            // Bắt đầu gửi
            btnSubmit.disabled = true;
            spinner.classList.remove('d-none');
            clearErrors();

            const url = isEditMode ? `/admin/products/${document.getElementById('product_id').value}` : '/admin/products';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    const text = await response.text();
                    console.error("Server returned non-JSON:", text);
                    showToast('Server trả về lỗi không định dạng (F12 để xem chi tiết)', 'error');
                    return;
                }

                if (!response.ok) {
                    if (response.status === 422) {
                        displayErrors(data.errors);
                    } else {
                        showToast(data.message || 'Đã có lỗi xảy ra', 'error');
                    }
                } else {
                    showToast(data.message || 'Thành công!', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                }
            } catch (error) {
                console.error("Fetch error:", error);
                showToast('Không thể kết nối đến máy chủ hoặc lỗi mạng', 'error');
            } finally {
                btnSubmit.disabled = false;
                spinner.classList.add('d-none');
            }
        }

        function deleteProduct(id) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Sản phẩm và toàn bộ hình ảnh, size của nó sẽ bị xóa vĩnh viễn!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Đồng ý, Xóa!',
                cancelButtonText: 'Hủy'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/admin/products/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok) {
                            showToast('Xóa sản phẩm thành công!', 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            showToast(data.message || 'Lỗi xóa sản phẩm', 'error');
                        }
                    } catch (error) {
                        showToast('Không thể kết nối đến máy chủ', 'error');
                    }
                }
            });
        }

        function displayErrors(errors) {
            for (const [key, messages] of Object.entries(errors)) {
                const input = document.getElementById(key);
                const errorDiv = document.getElementById(`error-${key}`);
                
                if (input && errorDiv) {
                    input.classList.add('is-invalid');
                    errorDiv.innerText = messages[0];
                }
            }
        }

        function clearErrors() {
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = '');
            document.getElementById('error-colors').innerText = '';
        }
    </script>
@endpush
