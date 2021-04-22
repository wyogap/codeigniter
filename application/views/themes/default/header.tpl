<!-- Topbar Start -->

<div class="navbar-custom topnav-navbar topnav-navbar-dark">
    <div class="container-fluid header">

        <ul class="list-unstyled topbar-right-menu float-right mb-0">

            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" id="topbar-userdrop"
                    href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="account-user-avatar">
                        <img src="{$userdata.profile_img}" alt="user-image" class="rounded-circle">
                    </span>
                    <span style="color: #fff;">
                        <span class="account-user-name">{$userdata.nama}</span>
                        <span class="account-position">{__($userdata.role)}</span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown"
                    aria-labelledby="topbar-userdrop">
                    <!-- item-->
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{__('Welcome')} !</h6>
                    </div>

                    <!-- Logout-->
                    <a href="{$base_url}crud/profile" class="dropdown-item notify-item">
                        <i class="mdi mdi-account mr-1"></i>
                        <span>{__('Profil')}</span>
                    </a>

                    <!-- Logout-->
                    <a href="{$base_url}auth/logout" class="dropdown-item notify-item">
                        <i class="mdi mdi-logout mr-1"></i>
                        <span>{__('Logout')}</span>
                    </a>

                </div>
            </li>

        </ul>

        <a class="button-menu-mobile disable-btn" style="width: 50px;">
            <div class="lines">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </a>

        <!-- LOGO -->
        <a href="{$base_url}{$page_role}/home" class="topnav-logo" style="min-width: unset; float: unset; line-height: unset; display: flex;">
            <div class="topnav-logo-lg" style="margin-top: 19px; ">
                <img src="{$base_url}{$app_logo}" alt="" height="32">
            </div>
            
            <div class="d-none d-md-flex"
                style="font-size: 14px; font-weight: 600; line-height: 32px; color: #fff; opacity: 1; text-transform: uppercase; margin-top: 19px; margin-left: 8px">
                {$app_name}</div>

            <div class="d-flex d-md-none"
                style="font-size: 14px; font-weight: 600; line-height: 32px; color: #fff; opacity: 1; text-transform: uppercase; margin-top: 19px; margin-left: 8px">
                {$app_short_name}</div>
        </a>

    </div>
</div>
<!-- end Topbar -->