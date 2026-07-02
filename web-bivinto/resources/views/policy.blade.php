@extends('layouts.app')

@section('title', 'Bivinto - Chính Sách')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/policy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/collaboration.css') }}">
@endpush

@section('content')
    <div class="policy-page pt-5">
        <div class="container text-center mb-5">
            <h1 class="section-title fw-bold mb-0">CHÍNH SÁCH</h1>
        </div>

        <div class="container policy-container">
            <div class="accordion policy-accordion" id="policyAccordion">
                
                <!-- Policy Item 1 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed fw-semibold" type="button" onclick="togglePolicyAccordion(this)">
                            Chính Sách Thành Viên
                        </button>
                    </h2>
                    <div class="policy-accordion-panel">
                        <div class="accordion-body">
                            <ul class="policy-list">
                                <li>Quyền lợi và chiết khấu dành cho thành viên Bivinto.</li>
                                <li>Điều kiện để nâng hạng thành viên và duy trì thứ hạng.</li>
                                <li>Các chương trình khuyến mãi đặc quyền trong các dịp Lễ, Tết, Sinh nhật.</li>
                                <li>Quy định về việc sử dụng điểm tích lũy khi mua sắm trực tiếp hoặc online.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Policy Item 2 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed fw-semibold" type="button" onclick="togglePolicyAccordion(this)">
                            Chính Sách Giao Hàng
                        </button>
                    </h2>
                    <div class="policy-accordion-panel">
                        <div class="accordion-body">
                            <ul class="policy-list">
                                <li>Nhận ship COD toàn quốc, thanh toán khi nhận hàng.</li>
                                <li>Đơn nội thành nhận trong ngày, với đơn ngoại thành nhận hàng sau 1-2 ngày. Những đơn hàng đi tỉnh tùy thuộc vào vị trí địa lý và hay gặp mà thời gian giao hàng có thể dao động từ 3-5 ngày.</li>
                                <li>Liên hệ hotline: <strong>+84 345 677 395</strong> để biết thông tin nhanh nhất về đơn hàng.</li>
                                <li>Miễn phí vận chuyển cho đơn hàng trên 1 triệu.</li>
                                <li>Hỗ trợ phí vận chuyển cho đơn hàng trên 500k (tối đa 30k) - Không áp dụng đối với sản phẩm sale.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Policy Item 3 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed fw-semibold" type="button" onclick="togglePolicyAccordion(this)">
                            Quy Định Đổi Trả
                        </button>
                    </h2>
                    <div class="policy-accordion-panel">
                        <div class="accordion-body">
                            <ul class="policy-list">
                                <li>Hỗ trợ đổi size/sản phẩm trong vòng 7 ngày kể từ ngày nhận hàng.</li>
                                <li>Sản phẩm đổi trả phải còn nguyên tem mác, chưa qua sử dụng và chưa giặt ủi.</li>
                                <li>Không áp dụng đổi trả với các sản phẩm trong chương trình khuyến mãi (Sale).</li>
                                <li>Khách hàng vui lòng chịu phí ship 2 chiều nếu đổi trả không do lỗi từ nhà sản xuất.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Policy Item 4 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed fw-semibold" type="button" onclick="togglePolicyAccordion(this)">
                            Chính Sách Xử Lý Khiếu Nại
                        </button>
                    </h2>
                    <div class="policy-accordion-panel">
                        <div class="accordion-body">
                            <ul class="policy-list">
                                <li>Bivinto cam kết giải quyết mọi khiếu nại của khách hàng trong vòng 24 - 48 giờ làm việc.</li>
                                <li>Trường hợp sản phẩm bị lỗi do nhà sản xuất (rách, bẩn, lỗi đường may), Bivinto sẽ chịu 100% phí đổi trả.</li>
                                <li>Trường hợp giao sai mẫu/sai size, chúng tôi sẽ tiến hành đổi sản phẩm đúng yêu cầu nhanh nhất có thể.</li>
                                <li>Mọi thắc mắc và khiếu nại vui lòng liên hệ hotline hoặc gửi tin nhắn trực tiếp qua Fanpage.</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Section 3: Liên hệ với chúng tôi (Reused from Collaboration) -->
        <section class="collab-contact-section mt-5">
            <div class="container-fluid p-0">
                <div class="row g-0 align-items-center">
                    <!-- Left: Map -->
                    <div class="col-12 col-lg-6 mb-5 mb-lg-0">
                        <div class="collab-contact-wrapper">
                            <iframe
                                src="https://maps.google.com/maps?q=S%E1%BB%91%2082%20Ph%E1%BB%91%20D%E1%BB%8Bch%20V%E1%BB%8Dng%20H%E1%BA%ADu%2C%20C%E1%BA%A7u%20Gi%E1%BA%A5y%2C%20H%C3%A0%20N%E1%BB%99i&t=&z=16&ie=UTF8&iwloc=&output=embed"
                                class="w-100 collab-map" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>

                    <!-- Right: Contact Form -->
                    <div class="col-12 col-lg-6">
                        <div class="collab-contact-wrapper">
                            <h3 class="contact-title fw-bold mb-2">Liên Hệ Với Chúng Tôi</h3>
                            <p class="contact-subtitle mb-4">Quý khách hàng vui lòng điền đầy đủ thông tin để đội ngũ
                                Bivinto tư vấn</p>

                            <form>
                                <div class="row gx-3 mb-3">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control collab-input" placeholder="Họ và tên*">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control collab-input"
                                            placeholder="Số điện thoại*">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control collab-input" placeholder="Email*">
                                </div>
                                <div class="mb-4">
                                    <textarea class="form-control collab-input" rows="4" placeholder="Message*"></textarea>
                                </div>

                                <div class="mt-4">
                                    <button type="submit"
                                        class="btn btn-dark px-5 py-2 rounded-pill collab-submit-btn mb-4">Gửi</button>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-dark fs-6 fw-semibold">Liên Hệ Ngay</span>
                                        <div class="social-contact d-flex align-items-center gap-2">
                                            <a href="#" class="social-icon-btn"><i
                                                    class="fa-brands fa-facebook-f"></i></a>
                                            <a href="#" class="social-icon-btn fw-bold"
                                                style="font-size: 0.75rem;">Zalo</a>
                                            <a href="#" class="social-icon-btn"><i class="fa-solid fa-phone"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    function togglePolicyAccordion(button) {
        const panel = button.parentElement.nextElementSibling;
        
        // Auto-close others to maintain accordion behavior
        document.querySelectorAll('.policy-accordion-panel').forEach(p => {
            if (p !== panel && p.classList.contains('show')) {
                p.classList.remove('show');
                p.style.maxHeight = "0px";
                const btn = p.previousElementSibling.querySelector('.accordion-button');
                if (btn) btn.classList.add('collapsed');
            }
        });

        // Toggle current
        button.classList.toggle('collapsed');
        panel.classList.toggle('show');

        if (panel.classList.contains('show')) {
            panel.style.maxHeight = panel.scrollHeight + "px";
        } else {
            panel.style.maxHeight = "0px";
        }
    }
</script>
@endpush
