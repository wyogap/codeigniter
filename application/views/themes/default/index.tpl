
<!DOCTYPE html>
<html>
<head>
    <title>{$page_title} | {$app_name}</title>
    <!-- all the meta tags -->
    {include "$theme_prefix/metas.tpl"}
    <!-- all the css files -->
    {include "$theme_prefix/includes_top.tpl"}
</head>
<body data-layout="detached">
    <!-- HEADER -->
    {include "$theme_prefix/header.tpl"}
    <div class="container-fluid">
        <div class="wrapper">
            <!-- BEGIN CONTENT -->
            <!-- SIDEBAR -->
            {include "$theme_prefix/$page_role/navigation.tpl"}
            <!-- PAGE CONTAINER-->
            <div class="content-page">
                <div class="content">
                    <!-- BEGIN PlACE PAGE CONTENT HERE -->
                    {include $inner_template}
                    <!-- END PLACE PAGE CONTENT HERE -->
                </div>
            </div>
            <!-- END CONTENT -->
        </div>
    </div>
    <!-- all the js files -->
    {include "$theme_prefix/includes_bottom.tpl"}
    {include "$theme_prefix/modal.tpl"}
    {include "$theme_prefix/common_scripts.tpl"}
</body>
</html>
