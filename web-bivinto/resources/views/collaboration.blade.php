@extends('layouts.app')

@section('title', 'Bivinto - Hợp Tác')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/collaboration.css') }}">
@endpush

@section('content')
    <div class="collab-page">
        <!-- Title & Banner -->
        <div class="collab-header text-center">
            <h1 class="section-title fw-bold mb-0">{{ get_setting('collab_title', 'HỢP TÁC') }}</h1>
        </div>

        @php
            $collabBannerImg = get_setting('collab_banner_image', '/images/banner-cooperate.png');
            $collabBannerSrc = str_starts_with($collabBannerImg, 'settings/') ? asset('storage/' . $collabBannerImg) : asset($collabBannerImg);
        @endphp
        <div class="collab-banner-wrapper">
            <img src="{{ $collabBannerSrc }}" alt="Hợp tác Bivinto"
                class="img-fluid w-100 object-fit-cover collab-banner-img">
        </div>

        <section class="collab-section p-0 mb-5">
            <h2 class="section-title text-center fw-bold mb-4 mt-5">{{ get_setting('collab_section1_title', 'LIÊN HỆ GIA CÔNG') }}</h2>

            <div class="container-fluid p-0 mt-4 mt-lg-5">
                <div class="row g-0 align-items-center">
                    <div class="col-12 col-lg-6">
                        @php
                            $collabImg1 = get_setting('collab_section1_image', '/images/about1.png');
                            $collabSrc1 = str_starts_with($collabImg1, 'settings/') ? asset('storage/' . $collabImg1) : asset($collabImg1);
                        @endphp
                        <img src="{{ $collabSrc1 }}" alt="Gia công"
                            class="img-fluid object-fit-cover collab-section-img w-100">
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="collab-text-content">
                            {!! get_setting('collab_section1_content', '<p>Bivinto được khơi nguồn từ một thời đại mới, nơi cơ thể tiếp cận với thời trang và phong cách sống hiện đại của nhiều bạn trẻ.</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Divider -->
        <div class="container-fluid p-0">
            <hr class="collab-divider">
        </div>

        <section class="collab-section p-0 pb-lg-5 mb-lg-5 pb-md-3 mb-md-3 pb-0 mb-0">
            <h2 class="section-title text-center fw-bold mb-4">{{ get_setting('collab_section2_title', 'LIÊN HỆ NHẬP SỈ') }}</h2>

            <div class="container-fluid p-0 mt-4 mt-lg-5">
                <div class="row g-0 align-items-center flex-column-reverse flex-lg-row">
                    <div class="col-12 col-lg-6">
                        <div class="collab-text-content">
                            {!! get_setting('collab_section2_content', '<p>Hành trình xây dựng Bivinto không bắt đầu bằng những điều vĩ đại.</p>') !!}
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        @php
                            $collabImg2 = get_setting('collab_section2_image', '/images/about2.png');
                            $collabSrc2 = str_starts_with($collabImg2, 'settings/') ? asset('storage/' . $collabImg2) : asset($collabImg2);
                        @endphp
                        <img src="{{ $collabSrc2 }}" alt="Nhập sỉ"
                            class="img-fluid object-fit-cover collab-section-img w-100">
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Liên hệ với chúng tôi -->
        <section class="collab-contact-section">
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
