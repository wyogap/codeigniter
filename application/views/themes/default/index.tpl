
<!DOCTYPE html>
<html>
<head>
    <title>{$page_title} - {$app_name}</title>
    <!-- all the meta tags -->
    {include "$theme_prefix/metas.tpl"}
    <!-- all the css files -->
    {include "$theme_prefix/includes_top.tpl"}

    <!-- page header -->
    {if !empty($header_view)}
    {include "$header_view"}
    {/if}

    {if !empty($page_header)}
    {$page_header}
    {/if}
</head>
<body data-layout="detached">
    <!-- HEADER -->
    {include "$theme_prefix/header.tpl"}
    <div class="container-fluid">
        <div class="wrapper">
            <!-- BEGIN CONTENT -->
            <!-- SIDEBAR -->
            {include "$theme_prefix/navigation.tpl"}
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
    {include "$theme_prefix/common_scripts.tpl"}

    <!-- page footer -->
    {if !empty($footer_view)}
    {include "$footer_view"}
    {/if}

    {if !empty($page_footer)}
    {$page_footer}
    {/if}
</body>
</html>
