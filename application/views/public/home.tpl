<!DOCTYPE html>
<html lang="en">

<head>

    <title>Home | {$app_name}</title>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Creativeitem" />

    <meta name="keywords" content="LMS,Learning Management System,Creativeitem,demo,hello,How are you" />
    <meta name="description" content="Nice application" />

    <link rel="shortcut icon" href="{$base_url}{$app_icon}">
    
    <!-- <link rel="favicon" href="http://localhost/academy/assets/frontend/default/img/icons/favicon.ico">
    <link rel="apple-touch-icon" href="http://localhost/academy/assets/frontend/default/img/icons/icon.png"> -->

    <!-- font awesome 5 -->
    <link href="{$base_url}assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" />

    <link href="{$base_url}assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700" rel="stylesheet">

    <link href="{$base_url}assets/jquery-confirm/jquery-confirm.min.css" rel="stylesheet" type="text/css" />
    <link href="{$base_url}assets/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

    <!-- jquery js. must be loaded first before other js -->
    <script src="{$base_url}assets/jquery/jquery-3.6.0.min.js"></script>

    <style>

    .nav-text {
        font-size: 14px;
        font-weight: 600;
        line-height: 32px;
        opacity: 1;
        text-transform: uppercase;
    }

    html {
        color: #222;
        font-size: 1em;
        line-height: 1.4;
    }

    :-moz-selection {
        background: #b3d4fc;
        text-shadow: none;
    }

    ::selection {
        background: #b3d4fc;
        text-shadow: none;
    }

    body {
        font-family: 'Open Sans', sans-serif;
        padding-right: 0 !important;
        color: #29303b;
        font-size: 15px;
    }

    body.white-bg {
        background: #fff;
    }

    body.gray-bg {
        background: #f7f8fa;
    }

    body.modal-open {
        overflow: auto;
    }

    p {
        margin: 0 0 10.5px;
    }

    /*
        bootstrap overwrite css
        */
    .container-xl,
    .container-lg {
        width: 100%;
        padding-right: 20px;
        padding-left: 20px;
        margin-right: auto;
        margin-left: auto
    }

    /*
        homepage styles
        */

    .home-banner-area {
        /* background-image: url('../../../../uploads/system/home-banner.jpg');
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat; */
        /* padding: 170px 0 130px; */
        color: #fff;
        background-color: #9e9e9e;
        background: -webkit-linear-gradient(-45deg, #9e9e9e, #607d8b);
        background: -moz-linear-gradient(-45deg, #ec5252 0, #6e1a52 100%);
        background: -ms-linear-gradient(-45deg, #ec5252 0, #6e1a52 100%);
        background: -o-linear-gradient(-45deg, #ec5252 0, #6e1a52 100%);
        background: linear-gradient(-45deg, #9e9e9e, #607d8b);
        padding: 50px 0 50px;
    }

    .home-banner-wrap {
        max-width: 600px;
    }

    .home-banner-wrap h2 {
        font-size: 44px;
        font-weight: 600;
        line-height: 1;
        margin-bottom: 10px;
        text-shadow: 0 2px 4px rgba(41, 48, 59, .55);
    }

    .home-banner-wrap p {
        font-size: 18px;
        line-height: 34px;
        margin-bottom: 30px;
        text-shadow: 0 2px 4px rgba(41, 48, 59, .55);
    }

    .home-banner-wrap input[type="text"] {
        font-size: 20px;
        height: 50px;
        padding: 11px 17px;
        border: none;
        border-radius: 3px 0 0 3px;
        font-weight: 300
    }

    .home-banner-wrap .btn {
        padding: 10px 14px;
        font-size: 20px;
        background: #fff;
        border: 0;
        border-radius: 0 3px 3px 0;
        color: #007bff;
    }

    .home-banner-wrap .btn:hover {
        background: #007bff;
        color: #fff;
    }

    .home-fact-area {
        background-color: #007bff;
        background: -webkit-linear-gradient(-45deg, #007bff, #17a2b8);
        background: -moz-linear-gradient(-45deg, #007bff 0, #17a2b8 100%);
        background: -ms-linear-gradient(-45deg, #007bff 0, #17a2b8 100%);
        background: -o-linear-gradient(-45deg, #007bff 0, #17a2b8 100%);
        background: linear-gradient(-45deg, #007bff, #17a2b8);
        color: #fff;
        padding: 15px 0;
        margin-bottom: 30px
    }

    .home-fact-box .text-box {
        padding: 10px 0 10px 63px;
    }

    .home-fact-box i {
        font-size: 47px;
        margin-top: 8px;
    }

    .home-fact-box .text-box h4 {
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 0;
    }

    .home-fact-box .text-box p {
        font-size: 15px;
        margin-bottom: 0;
    }

    .menu-area {
        box-shadow: 0 0 1px 1px rgba(20, 23, 28, .1), 0 3px 1px 0 rgba(20, 23, 28, .1);
        position: relative;
        z-index: 99;
    }

    .menu-area .navbar {
        padding: 0;
    }

    .course-carousel-area {
        margin-bottom: 20px;
        overflow-x: hidden;
    }

    .course-carousel-area .course-carousel-title {
        font-size: 20px;
        color: #505763;
        margin: 0 0 10px;
    }

    .course-carousel-area .slick-slider {
        width: calc(100% + 16px);
        /* margin-left: -8px; */
    }

    .course-carousel-area .slick-list:before,
    .course-carousel-area .slick-list:after {
        position: absolute;
        content: "";
        top: 0;
        right: 0;
        height: 100%;
        width: 8px;
        background: #f7f8fa;
        z-index: 1;
    }

    .course-carousel-area .slick-list:after {
        right: auto;
        left: 0
    }

    .course-carousel .slick-prev:hover,
    .course-carousel .slick-next:hover {
        box-shadow: 0 2px 8px 2px rgba(20, 23, 28, .15);
    }

    .course-carousel .slick-prev:focus,
    .course-carousel .slick-next:focus {
        box-shadow: 0 0 1px 1px rgba(20, 23, 28, .1), 0 3px 1px 0 rgba(20, 23, 28, .1) !important;
    }

    .course-carousel .slick-prev,
    .course-carousel .slick-next {
        width: 47px;
        height: 47px;
        border-radius: 50%;
        background-color: #fff;
        box-shadow: 0 0 1px 1px rgba(20, 23, 28, .1), 0 3px 1px 0 rgba(20, 23, 28, .1);
        z-index: 1;
        top: calc(50% - 25px);
    }

    .course-carousel .slick-prev {
        left: -28px;
    }

    .course-carousel .slick-prev.slick-disabled,
    .course-carousel .slick-next.slick-disabled {
        opacity: 0;
    }

    .course-carousel .slick-prev:before {
        content: url(../img/icons/prev_arrow.png);
        line-height: 0;
        opacity: 1
    }

    .course-carousel .slick-next {
        right: -15px
    }

    .course-carousel .slick-next:before {
        content: url(../img/icons/next_arrow.png);
        line-height: 0;
        opacity: 1;
    }

    ul.dtr-details {
        display: inline-block;
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    ul.dtr-details > li:first-child {
        padding-top: 0;
    }

    ul.dtr-details > li {
        border-bottom: 1px solid #efefef;
        padding: 0.5em 0;
    }

    span.dtr-title {
        display: inline-block;
        min-width: 75px;
        font-weight: bold;
    }

    .navbar .account-user-avatar img {
        height: 32px;
        width: 32px;
    }

    .notification-list .dropdown-menu.dropdown-menu-right {
        -webkit-transform: none!important;
        transform: none!important;
        top: 100%!important;
        right: 0!important;
        left: auto!important;
    }

    .dropdown-toggle::after {
        display: none;
    }
    </style>
</head>

<body class="gray-bg">
    <section class="menu-area">
        <div class="container-xl">
            <div class="row">
                <nav class="navbar navbar-expand-lg navbar-light bg-light" style="width: 100%;">
                    <!--  sign-in-box start -->
                    <!-- <div class="btn-group float-right">

                        <a href="http://localhost/academy/home/login" class="btn btn-sign-in">Log in
                        </a>

                    </div>  -->
                    <!--  sign-in-box end -->

                    {if empty($userdata.is_logged_in)}
                    <div class="topnav-logo"
                        style="min-width: unset; float: unset; line-height: unset; display: flex; align-content: center; align-items: center; padding: 0.5rem 0.5rem; flex-grow: 1;">
                        <div class="topnav-logo-lg" style="">
                            <img src="{$base_url}{$app_logo}" alt="" height="32">
                        </div>

                        <div class="d-none d-md-flex nav-text" style="margin-left: 8px; color: #505763;">
                            {$app_name}</div>

                        <div class="d-flex d-md-none nav-text" style="margin-left: 8px; color: #505763;">
                            {$app_short_name}</div>
                    </div>

                    <div class="d-none d-md-flex" style="padding: 0.5rem 0.5rem;">
                        <div role="form" enctype="multipart/form-data" id="proses"
                            action="http://localhost/codeigniter/auth/login/" method="post"
                            class="has-validation-callback">
                            <div class="input-group" style="display: inline-flex; width: 200px;">
                                <input type="text" class="form-control" placeholder="Username" id="username"
                                    name="username" data-validation="required" minlength="4" maxlength="100">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mx-1" style="display: inline-flex; width: 200px;">
                                <input type="password" class="form-control" placeholder="Password" id="password"
                                    name="password" data-validation="required">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>


                            <div class="" style="display: inline-flex; width: 100px;">
                                <button type="submit" class="btn btn-primary btn-block btn-sign-in">Masuk</button>
                            </div>

                            <!-- 
                        <div class="row">
                            <div class="col-12">
                            <p class="mb-3">
                                <a href="forgot-password.html">Lupa password saya</a>
                            </p>
                            </div>
                        </div> 
                        -->
                        </div>
                    </div>
                    <div class="btn-group d-md-none" style="padding: 0.5rem 0.5rem">

                        <a href="{$site_url}auth/login" class="d-flex nav-text"
                            style="align-content: center; align-items: center;   color: unset">Masuk<i
                                class="fa fa-sign-in-alt fa-2x ml-2"></i></a>

                        <!-- <a href="http://localhost/academy/home/login" class="btn btn-sm btn-sign-in">Log in
                        </a> -->

                    </div>
                    {else}
                    <a href="{$site_url}{$userdata.page_role}/home" class="topnav-logo"
                        style="min-width: unset; float: unset; line-height: unset; display: flex; align-content: center; align-items: center; padding: 0.5rem 0.5rem; flex-grow: 1;">
                        <div class="topnav-logo-lg" style="">
                            <img src="{$base_url}{$app_logo}" alt="" height="32">
                        </div>

                        <div class="d-none d-md-flex nav-text" style="margin-left: 8px; color: #505763;">
                            {$app_name}</div>

                        <div class="d-flex d-md-none nav-text" style="margin-left: 8px; color: #505763;">
                            {$app_short_name}</div>
                    </a>

                    <div class="d-none d-md-flex" style="padding: 0.5rem 0.5rem;">
                    {__('Selamat Datang')}, {$userdata.nama}!
                    </div>

                    <ul class="list-unstyled topbar-right-menu float-right mb-0">

                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" id="topbar-userdrop"
                                href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="account-user-avatar">
                                    <img src="{$userdata.profile_img}" alt="user-image" class="rounded-circle">
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown"
                                aria-labelledby="topbar-userdrop">
                                <!-- item-->
                                <div class=" dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">{__('Welcome')}, {$userdata.nama}!</h6>
                                </div>

                                <!-- Logout-->
                                <a href="{$site_url}crud/profile" class="dropdown-item notify-item">
                                    <i class="mdi mdi-account mr-1"></i>
                                    <span>{__('Profil')}</span>
                                </a>

                                <!-- Logout-->
                                <a href="{$site_url}auth/logout" class="dropdown-item notify-item">
                                    <i class="mdi mdi-logout mr-1"></i>
                                    <span>{__('Logout')}</span>
                                </a>

                            </div>
                        </li>

                    </ul>
        {/if}
                </nav>
            </div>
        </div>
    </section>

    <section class="home-banner-area">
        <div class="container-lg">
            <div class="row">
                <div class="col">
                    <div class="home-banner-wrap">
                        <h2>Informasi kendaraan dinas</h2>
                        <br/><br/>
                        <div class="" action="{$site_url}rest/api_kendaraan_dinas" method="get">
                            <div class="input-group">
                                <input type="text" class="form-control" name="query" placeholder="Pencarian"
                                    id="input-search">
                                <div class="input-group-append">
                                    <button class="btn btn-search" type="submit"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <p style="margin-top:12px; line-height: 27px; margin-bottom: 0px;">Masukkan kata kunci (nomor polisi, nama skpd, nama pengguna atau kata kunci lainnya)</p> 
                     </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-fact-area">
        <div class="container-lg">
            <div class="row">
                <div class="col-md-12">
                    <p style="margin-bottom: 0px;">{$newsflash}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="course-carousel-area">
        <div class="container-lg">
            <div class="row">
                <div class="col">
                    <h2 class="course-carousel-title" id="result-text" style="margin-bottom: 12px;">
                        <div style="font-size: 15px;">{$description}</div>
                    </h2>
                    <div class="course-carousel row" id="result-list">
                    </div>
                </div>
            </div>
        </div>
    </section>


    <footer class="footer-area d-print-none d-none">
        <div class="container-xl">
            <div class="row">

            </div>
        </div>
    </footer>

    <!-- PAYMENT MODAL -->
    <!-- Modal -->

    <script src="{$base_url}assets/bootstrap/js/bootstrap.bundle.min.js" defer></script>

    <!-- mustache templating -->
    <script src="{$base_url}assets/mustache/mustache.min.js" defer></script>

    <!-- toastr toast popup -->
    <script src="{$base_url}assets/jquery-confirm/jquery-confirm.min.js"></script>
    <script src="{$base_url}assets/toastr/toastr.min.js"></script>

</body>

<script type="text/javascript">
$(document).ready(function() {

    $(".btn-sign-in").on("click", function(e) {
        signin();
    });

    $('#input-search').on("keyup", function(e) {
        if (e.which == 13) {
            search();
        }
    });

    $(".btn-search").on("click", function(e) {
        search();
    });
});

function signin() {
    $.ajax({
        "url": "{$site_url}auth/login",
        "dataType": "json",
        "type": "POST",
        "data": {
            'username': $("#username").val(),
            'password': $("#password").val(),
            'json': 1
        },
        beforeSend: function(request) {
            request.setRequestHeader("Content-Type",
                "application/x-www-form-urlencoded; charset=UTF-8");
        },
        success: function(response) {
            let data = [];
            if (typeof response.error !== 'undefined' && response.error !== null &&
                response.error != "") {
                alert(response.error);
                return;
            }

            //redirect
            if (response.status == 1 && response.redirect != null && response.redirect != "") {
                window.location.href = response.redirect;
            }
            else {
                alert("{__('Gagal melakukan login')}");
            }
        },
        error: function(jqXhr, textStatus, errorMessage) {
            alert("{__('Gagal melakukan login')}");

        }
    });
}

function search() {
    $.ajax({
        "url": "{$site_url}rest/api_kendaraan_dinas?search=" + $("#input-search").val(),
        "dataType": "json",
        "type": "GET",
        "data": {
            'json': 1
        },
        beforeSend: function(request) {
            request.setRequestHeader("Content-Type",
                "application/x-www-form-urlencoded; charset=UTF-8");
        },
        success: function(response) {
            //display result
            if (response.result === "undefined" || response.result == null || response.result.length == 0) {
                $('#result-text').html("Data tidak ditemukan.");
                $('#result-list').html("");
            }
            else if (response.result.length > {$max_search_result}) {
                $('#result-text').html("Hasil pencarian terlalu banyak. Hanya menampilkan {$max_search_result} entri pertama dari " +response.result.length+ " total.");
                $('#result-list').html("");
                for (let i=0; i<response.result.length && i<{$max_search_result}; i++) {
                    let item = response.result[i];
                    let content = render_template($("#result-info"), item);
                    $('#result-list').append(content);
                }
            }
            else {
                $('#result-text').html("Hasil pencarian (" +response.result.length+ " entri):");
                $('#result-list').html("");
                response.result.forEach(function(item, index) {
                    let content = render_template($("#result-info"), item);
                    $('#result-list').append(content);
                })
            }
        },
        error: function(jqXhr, textStatus, errorMessage) {
            let code = jqXhr.status;
            let json = jqXhr.responseJSON;

            let msg = "";
            if (code == 404) {
                msg = "{__('Data tidak ditemukan')}";
            }
            else if (typeof json.message !== 'undefined' && json.message !== null && json.message != "") {
                msg = json.message;
            }
            else {
                msg = "{__('Gagal melakukan pencarian')}"
            }
            $('#result-text').html(msg);
        }
    });
}

    // render template
    function render_template(selector, options) {
        var template = $(selector).html();
        Mustache.parse(template);
        var rendered_template = Mustache.render(template, options);
        return rendered_template;
    }

</script>

<script id="result-info" type="text/template">
    <div class="col-sm-12 col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <ul data-dtr-index="3" class="dtr-details">
                    <li data-dtr-index="1" data-dt-column="1">
                        <span class="dtr-title">OPD</span> 
                        <span class="dtr-data">{literal}{{opd_label}}{/literal}</span>
                    </li>
                    <li data-dtr-index="1" data-dt-column="1">
                        <span class="dtr-title">No. Polisi</span> 
                        <span class="dtr-data">{literal}{{no_polisi}}{/literal}</span>
                    </li>
                    <li data-dtr-index="1" data-dt-column="1">
                        <span class="dtr-title">Merek</span> 
                        <span class="dtr-data">{literal}{{merek_label}}{/literal}</span>
                    </li>
                    <!-- <li data-dtr-index="1" data-dt-column="1">
                        <span class="dtr-title">Model</span> 
                        <span class="dtr-data">{literal}{{model_label}}{/literal}</span>
                    </li>
                    <li data-dtr-index="1" data-dt-column="1">
                        <span class="dtr-title">Warna</span> 
                        <span class="dtr-data">{literal}{{warna}}{/literal}</span>
                    </li> -->
                    <li data-dtr-index="1" data-dt-column="1">
                        <span class="dtr-title">Nama Pengguna</span> 
                        <span class="dtr-data">{literal}{{nama_pengguna}}{/literal}</span>
                    </li>
                    <li data-dtr-index="1" data-dt-column="1">
                        <span class="dtr-title">Peruntukan</span> 
                        <span class="dtr-data">{literal}{{peruntukan_label}}{/literal}</span>
                    </li>
                    <li data-dtr-index="1" data-dt-column="1">
                        <span class="dtr-title">Tahun Perolehan</span> 
                        <span class="dtr-data">{literal}{{tahun}}{/literal}</span>
                    </li>
                    <li data-dtr-index="1" data-dt-column="1">
                        <span class="dtr-title">Tanggal Bayar Pajak</span> 
                        <span class="dtr-data">{literal}{{tanggal_bayar_pajak}}{/literal}</span>
                    </li>
                    <li data-dtr-index="1" data-dt-column="1">
                        <span class="dtr-title">No. Telp. Kantor</span> 
                        <span class="dtr-data">{literal}{{no_telp_kantor_pengguna}}{/literal}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</script>

</html>