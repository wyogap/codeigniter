<!-- Topbar Start -->

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" id="tcg-navbar-toggler"><i
                    class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
            <div class="nav-link page-title d-md-none"><b>{$app_short_name}</b></div>
            <div class="nav-link page-title d-none d-md-block"><b>{$app_name}</b></div>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown user user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <img
                    src="{$userdata.profile_img}" class="user-image img-circle elevation-2"
                    alt="User Image" style="width: "></a>
            <ul class="dropdown-menu dropdown-menu dropdown-menu-right" style="width:250px; z-index:1035">
                <li class="user-header bg-primary">
                    <img src="{$userdata.profile_img}" class="img-circle elevation-2"
                        alt="User Image">

                    <p>
                    {$userdata.nama} <small>{__($userdata.role)}</small>
                    </p>
                </li>

                <li class="user-footer">
                    <div class="pull-left" style="display: inline-block;">
                        <a href="{$site_url}crud/profile" class="btn btn-default btn-flat">{__('Profil')}</a>
                    </div>
                    <div class="pull-right" style="display: inline-block; float: right;">
                        <a href="{$site_url}auth/logout" class="btn btn-default btn-flat">{__('Logout')}</a>
                    </div>
                </li>
            </ul>

        </li>
    </ul>

</nav>
<!-- end Topbar -->