@extends('layouts.app')

@section('title', 'Bivinto - Chính Sách')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/policy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/collaboration.css') }}">
@endpush

@section('content')
    <div class="policy-page pt-5">
        <div class="container text-center mb-5">
            <h1 class="section-title fw-bold mb-0">{{ get_setting('policy_title', 'CHÍNH SÁCH') }}</h1>
        </div>

        <div class="container policy-container">
            <div class="accordion policy-accordion" id="policyAccordion">

                @for($i = 1; $i <= 4; $i++)
                    @php
                        $pTitle = get_setting("policy{$i}_title");
                        $pContent = get_setting("policy{$i}_content");
                    @endphp
                    @if($pTitle)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button" onclick="togglePolicyAccordion(this)">
                                {{ $pTitle }}
                            </button>
                        </h2>
                        <div class="policy-accordion-panel">
                            <div class="accordion-body">
                                {!! $pContent !!}
                            </div>
                        </div>
                    </div>
                    @endif
                @endfor

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
                            <h3 class="contact-title fw-bold mb-2">{{ get_setting('collab_contact_title', 'Liên Hệ Với Chúng Tôi') }}</h3>
                            <p class="contact-subtitle mb-4">{{ get_setting('collab_contact_subtitle', 'Quý khách hàng vui lòng điền đầy đủ thông tin để đội ngũ Bivinto tư vấn') }}</p>

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
                                            <a href="{{ get_setting('footer_facebook', '#') }}" class="social-icon-btn"><i
                                                    class="fa-brands fa-facebook-f"></i></a>
                                            <a href="{{ get_setting('footer_zalo', '#') }}" class="social-icon-btn fw-bold"
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
