@extends('layouts.admin')

@section('title', 'Quản lý Danh mục')
@section('page-title', 'Quản lý Danh mục')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-dark">Danh sách Danh mục</h5>
            <button class="btn btn-dark btn-sm rounded-pill px-3" onclick="openCreateModal()">
                <i class="fa-solid fa-plus me-1"></i> Thêm mới
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 px-4" width="5%">#</th>
                            <th class="border-0">Tên danh mục</th>
                            <th class="border-0 text-center">Nổi bật</th>
                            <th class="border-0 text-center">Thứ tự</th>
                            <th class="border-0">Danh mục cha</th>
                            <th class="border-0">Trạng thái</th>
                            <th class="border-0 text-center" width="15%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr id="row-{{ $category->id }}">
                                <td class="px-4 text-muted">{{ $category->id }}</td>
                                <td class="fw-medium text-dark">{{ $category->name }}</td>
                                <td class="text-center">
                                    @if ($category->is_featured)
                                        <i class="fa-solid fa-star text-warning"></i>
                                    @else
                                        <i class="fa-regular fa-star text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">{{ $category->display_order }}</td>
                                <td>
                                    @if ($category->parent)
                                        <span class="badge bg-secondary">{{ $category->parent->name }}</span>
                                    @else
                                        <span class="text-muted fst-italic">-- Trống --</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($category->status === 'active')
                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Hoạt
                                            động</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">Đang
                                            ẩn</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light text-primary me-1"
                                        onclick="openEditModal({{ $category->id }})" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-danger"
                                        onclick="deleteCategory({{ $category->id }})" title="Xóa">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Chưa có danh mục nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="categoryForm" onsubmit="submitForm(event)">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title fw-bold" id="categoryModalLabel">Thêm Danh Mục</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="category_id">

                        <div class="mb-3">
                            <label class="form-label fw-medium">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" required
                                placeholder="Nhập tên danh mục">
                            <div class="invalid-feedback" id="error-name"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Danh mục cha</label>
                            <select class="form-select" id="parent_id">
                                <option value="">-- Không có (Danh mục gốc) --</option>
                                @foreach ($parentCategories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-parent_id"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Mô tả</label>
                            <textarea class="form-control" id="description" rows="3" placeholder="Nhập mô tả (không bắt buộc)"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Thứ tự hiển thị</label>
                                <input type="number" class="form-control" id="display_order" value="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Trạng thái</label>
                                <select class="form-select" id="status">
                                    <option value="active">Hoạt động</option>
                                    <option value="inactive">Đang ẩn</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_featured" value="1">
                            <label class="form-check-label fw-medium" for="is_featured">Nổi bật</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 bg-light rounded-bottom">
                        <button type="button" class="btn btn-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-dark rounded-pill px-4" id="btnSubmit">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
        const form = document.getElementById('categoryForm');
        const modalTitle = document.getElementById('categoryModalLabel');
        const btnSubmit = document.getElementById('btnSubmit');

        function resetForm() {
            form.reset();
            document.getElementById('category_id').value = '';
            document.getElementById('is_featured').checked = false;
            document.getElementById('display_order').value = '0';
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        }

        function openCreateModal() {
            resetForm();
            modalTitle.textContent = 'Thêm Danh Mục';
            categoryModal.show();
        }

        async function openEditModal(id) {
            resetForm();
            modalTitle.textContent = 'Đang tải...';
            categoryModal.show();

            try {
                const response = await fetch(`/admin/categories/${id}/edit`);
                const data = await response.json();

                if (response.ok) {
                    modalTitle.textContent = 'Sửa Danh Mục';
                    document.getElementById('category_id').value = data.id;
                    document.getElementById('name').value = data.name;
                    document.getElementById('parent_id').value = data.parent_id || '';
                    document.getElementById('description').value = data.description || '';
                    document.getElementById('status').value = data.status;
                    document.getElementById('is_featured').checked = data.is_featured;
                    document.getElementById('display_order').value = data.display_order;
                } else {
                    categoryModal.hide();
                    Swal.fire('Lỗi', 'Không lấy được dữ liệu.', 'error');
                }
            } catch (error) {
                categoryModal.hide();
                Swal.fire('Lỗi', 'Mất kết nối máy chủ.', 'error');
            }
        }

        async function submitForm(e) {
            e.preventDefault();

            const id = document.getElementById('category_id').value;
            const url = id ? `/admin/categories/${id}` : '/admin/categories';
            const method = id ? 'PUT' : 'POST';

            const data = {
                name: document.getElementById('name').value,
                parent_id: document.getElementById('parent_id').value || null,
                description: document.getElementById('description').value,
                status: document.getElementById('status').value,
                is_featured: document.getElementById('is_featured').checked ? 1 : 0,
                display_order: document.getElementById('display_order').value || 0,
                // Laravel needs CSRF token for web routes (which we are using instead of api for admin)
                _token: '{{ csrf_token() }}'
            };

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...';

            // Reset errors
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    categoryModal.hide();

                    // Show toast
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: result.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });

                } else if (response.status === 422) {
                    // Validation error
                    for (const [field, messages] of Object.entries(result.errors)) {
                        const input = document.getElementById(field);
                        const errorDiv = document.getElementById('error-' + field);
                        if (input && errorDiv) {
                            input.classList.add('is-invalid');
                            errorDiv.textContent = messages[0];
                        }
                    }
                } else {
                    Swal.fire('Lỗi', result.message || 'Có lỗi xảy ra.', 'error');
                }
            } catch (error) {
                Swal.fire('Lỗi', 'Mất kết nối máy chủ.', 'error');
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.textContent = 'Lưu lại';
            }
        }

        function deleteCategory(id) {
            Swal.fire({
                title: 'Xóa danh mục?',
                text: "Dữ liệu sẽ không thể khôi phục!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Vâng, xóa nó!',
                cancelButtonText: 'Hủy'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/admin/categories/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Lỗi', data.message || 'Không thể xóa.', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Lỗi', 'Mất kết nối máy chủ.', 'error');
                    }
                }
            });
        }
    </script>
@endpush
