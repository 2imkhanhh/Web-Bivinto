@extends('layouts.app')

@section('title', 'Bivinto - Về Bivinto')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/about-us.css') }}">
@endpush

@section('content')
    <section class="about-hero">
        <div class="container-fluid px-0">
            <h1 class="about-hero-title">
                HÀNH TRÌNH XÂY DỰNG<br>PHONG THÁI ĐÀN ÔNG VIỆT
            </h1>
            <div class="about-hero-image-wrapper">
                <!-- Sử dụng banner hoặc hình ảnh phù hợp. Có thể thay 'pic2.png' bằng ảnh thật của trang about -->
                <img src="{{ asset('images/banner-aboutus.png') }}" alt="Hành trình xây dựng phong thái đàn ông Việt"
                    class="about-hero-image">
            </div>
        </div>
    </section>

    <!-- Story Section -->
    <section class="about-story-section">
        <div class="container-fluid p-0">
            <!-- Top Text Row -->
            <div class="row g-0 about-story-header">
                <div class="col-md-6 d-flex flex-column justify-content-center story-header-left">
                    <h2 class="about-hero-title mb-2">BIVINTO</h2>
                    <p class="about-subtitle m-0">“Nơi Mọi Hành Trình Mạnh Mẽ Bắt Đầu”</p>
                </div>
                <div class="col-md-6 d-flex align-items-center story-header-right">
                    <div class="row g-2 g-md-4 w-100 m-0">
                        <div class="col-sm-6 ps-0">
                            <p class="story-small-text m-0">Bivinto không bắt đầu từ những trung tâm thời trang sôi động hay
                                những cơ hội sẵn có.</p>
                        </div>
                        <div class="col-sm-6 pe-0">
                            <p class="story-small-text m-0">Thương hiệu được hình thành từ một khát vọng rất giản đơn: "Giúp
                                đàn ông Việt dễ dàng tiếp cận thời trang phù hợp với phong thái và sự tự tin hơn trên hành
                                trình chinh phục mục tiêu".</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid Row 1 -->
            <div class="row g-0">
                <div class="col-md-6 d-flex align-items-end">
                    <img src="{{ asset('images/about1.png') }}" class="w-100 object-fit-cover story-grid-img"
                        alt="Bivinto Khởi Nguồn">
                </div>
                <div class="col-md-6 d-flex flex-column justify-content-center story-text-box">
                    <p class="about-desc mb-3 mb-md-4">Bivinto được khởi nguồn từ một tỉnh miền núi, nơi cơ hội tiếp cận với
                        thời
                        trang và phong cách sống hiện đại còn nhiều hạn chế.</p>
                    <p class="about-desc mb-3 mb-md-4">Trong khi nhiều người có điều kiện để lựa chọn và xây dựng hình ảnh
                        cá nhân,
                        rất nhiều đàn ông trẻ lại gặp khó khăn trong việc tìm kiếm những trang phục phù hợp với vóc dáng,
                        môi trường làm việc và phong cách sống của mình.</p>
                    <p class="about-desc m-0">Từ chính những trải nghiệm đó, một câu hỏi luôn xuất hiện: <strong>"Điều gì
                            đang ngăn cản đàn ông Việt thể hiện khí chất thật sự của họ?"</strong>. Câu hỏi ấy đã trở thành
                        động lực cho sự ra đời của Bivinto.</p>
                </div>
            </div>

            <!-- Grid Row 2 -->
            <div class="row g-0 flex-column-reverse flex-md-row">
                <div class="col-md-6 d-flex flex-column justify-content-center story-text-box">
                    <p class="about-desc mb-3 mb-md-4">Hành trình xây dựng Bivinto không bắt đầu bằng những điều thuận lợi.
                    </p>
                    <p class="about-desc mb-3 mb-md-4">Từ việc nghiên cứu sản phẩm, tìm kiếm đối tác sản xuất, kiểm soát
                        chất lượng,
                        xây dựng đội ngũ cho đến phát triển thương hiệu, mọi bước đi đều là những thử thách mới. Nhưng cũng
                        giống như hành trình trưởng thành của mỗi người đàn ông, chính những khó khăn ấy đã tạo nên bản lĩnh
                        và sự kiên định.</p>
                    <p class="about-desc m-0">Bivinto tin rằng: <strong>"Điều tạo nên sự khác biệt không phải những gì ta
                            đang có, mà là những gì ta dám theo đuổi"</strong>. Đó cũng là tinh thần mà thương hiệu muốn
                        truyền tải đến khách hàng thông qua từng sản phẩm và trải nghiệm.</p>
                </div>
                <div class="col-md-6 d-flex align-items-start">
                    <!-- Ảnh người đàn ông mặc suit -->
                    <img src="{{ asset('images/about2.png') }}" class="w-100 object-fit-cover story-grid-img"
                        alt="Bivinto Hành Trình">
                </div>
            </div>
        </div>
    </section>

    <!-- Meaning Section -->
    <section class="about-meaning-section py-5">
        <div class="container-fluid text-center">
            <hr class="meaning-divider mx-auto">

            <h2 class="about-hero-title mb-5">
                Ý NGHĨA<br>THƯƠNG HIỆU
            </h2>

            <div class="meaning-content mx-auto">
                <h4 class="meaning-subtitle mb-4">BIVINTO – TRỞ THÀNH NGƯỜI CHINH PHỤC</h4>

                <p class="meaning-text mb-4">Tên gọi Bivinto được tạo nên từ 3 yếu tố:</p>

                <ul class="list-unstyled meaning-list mb-4">
                    <li><strong>Be</strong> - Đại diện cho sự trưởng thành và hành trình trở thành phiên bản tốt hơn của
                        chính mình.</li>
                    <li><strong>Vincere</strong> - Trong tiếng Latin mang ý nghĩa "chinh phục", tượng trưng cho bản lĩnh, ý
                        chí và khát vọng tiến lên.</li>
                    <li><strong>To</strong> - Thể hiện sự hướng tới mục tiêu, tinh thần chủ động và không ngừng phát triển.
                    </li>
                </ul>

                <p class="meaning-text m-0">Kết hợp lại, Bivinto mang ý nghĩa: Trở thành người chinh phục –
                    Hướng tới thành công.</p>
            </div>
        </div>
    </section>

    <!-- Belief Section -->
    <section class="about-belief-section py-5">
        <div class="container px-0 py-md-4">
            <div class="row m-0">
                <div class="col-lg-6 ps-0">
                    <h2 class="about-title mb-5 mb-lg-0 text-start">CHÚNG TÔI TIN RẰNG</h2>
                </div>
                <div class="col-lg-6 ps-lg-5 pe-0">
                    <div class="belief-item">
                        <h4 class="meaning-subtitle">THỜI TRANG<br>KHÔNG CHỈ LÀ QUẦN ÁO</h4>
                        <p class="meaning-text mt-3 mb-0">Đó là cách một người đàn ông thể hiện sự tôn trọng với chính mình và những người xung quanh.</p>
                    </div>
                    <hr class="belief-divider">
                    <div class="belief-item">
                        <h4 class="meaning-subtitle">SỰ TỰ TIN BẮT ĐẦU<br>TỪ SỰ PHÙ HỢP</h4>
                        <p class="meaning-text mt-3 mb-0">Một bộ trang phục đúng gu, vừa vặn và chỉn chu có thể thay đổi cách một người xuất hiện mỗi ngày.</p>
                    </div>
                    <hr class="belief-divider">
                    <div class="belief-item">
                        <h4 class="meaning-subtitle">ĐƠN GIẢN<br>NHƯNG KHÁC BIỆT</h4>
                        <p class="meaning-text mt-3 mb-0">Bivinto không chạy theo xu hướng nhất thời. Chúng tôi tập trung vào những giá trị bền vững, tính ứng dụng cao và sự tinh tế trong từng chi tiết.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
