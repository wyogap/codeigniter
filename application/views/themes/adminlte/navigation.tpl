
{if !empty($navigation)} 
{if $navigation|@count > 0}
<aside class="main-sidebar elevation-4 sidebar-light-info">

    <a href="{$site_url}{$page_role}" class="brand-link">
        <img src="{$base_url}/{$app_logo}" alt="{$app_name}" class="brand-image elevation-3">
        <span class="brand-text font-weight-light">{$app_short_name}</span>
    </a>

    <div class="sidebar" style="overflow: hidden !important;">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            
            <div class="image">
                <img src="{$base_url}/images/home.jpeg" class="img-circle elevation-2" alt="User Image">
                <!-- <img src="{$userdata.profile_img}" class="img-circle elevation-2" alt="User Image"> -->
            </div>
            <div class="info">
                <a href="{$site_url}{$page_role}">
                <div class="d-block">{$userdata.nama}</div>
                </a>
            </div>
            
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class  with font-awesome or any other icon font library -->

                {if !empty($level1_title)}
                <li class="side-nav-title side-nav-item">{$level1_title}</li>
                {/if}

                {foreach $navigation as $nav}
                {if $nav.nav_type == 'title'}
                <li class="nav-header">{__($nav.label)}</li>
                {else if $nav.nav_type == 'item'}
                {if $nav.action_type == 'page'}
                <li class="nav-item">
                    <a href="{$site_url}{$controller}/{$nav.page_name}{if !empty($nav.page_param)}{$nav.page_param}{/if}" 
                       class="nav-link {if $page_name==$nav.page_name || (!empty($page_tag) && !empty($nav.nav_tag) && $page_tag==$nav.nav_tag)}active{/if}">
                        <i class="{$nav.icon}"></i>
                        <p>{__($nav.label)}</p>
                    </a>
                </li>
                {else if $nav.action_type == 'url'}
                <li class="nav-item">
                    <a href="{$nav.url}" class="nav-link">
                        <i class="{$nav.icon}"></i>
                        <p>{__($nav.label)}</p>
                    </a>
                </li>
                {else if $nav.action_type == 'param_url'}
                <li class="nav-item">
                    <a href="{$site_url}{$nav.url}" class="nav-link">
                        <i class="{$nav.icon}"></i>
                        <p>{__($nav.label)}</p>
                    </a>
                </li>
                {else if $nav.action_type == 'dropdown'}

                {assign var=page_selected value='0'}
                {if in_array($page_name,$nav.pages) || (!empty($page_tag) && in_array($page_tag,$nav.tags))}
                {assign var=page_selected value='1'}
                {/if}
                
                <li class="nav-item has-treeview {if $page_selected==1}menu-open{/if}">
                    <a href="javascript: void(0);" class="nav-link {if $page_selected==1}active{/if}">
                        <i class="{$nav.icon}"></i>
                        <p>{__($nav.label)}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        {foreach $nav.subitems as $subitem}
                        {if $subitem.action_type == 'page'}
                        <li class="nav-item">
                            <a href="{$site_url}{$controller}/{$subitem.page_name}{if !empty($nav.page_param)}{$nav.page_param}{/if}"
                                class="nav-link {if $page_name=={$subitem.page_name} || (!empty($page_tag) && !empty($subitem.nav_tag) && $page_tag==$subitem.nav_tag)}active{/if}"><i
                                    class="far fa-circle nav-icon"></i>
                                <p>{__($subitem.label)}</p>
                            </a>
                        </li>
                        {else if $subitem.action_type == 'url'}
                        <li class="nav-item">
                            <a href="{$subitem.url}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{__($subitem.label)}</p>
                            </a>
                        </li>
                        {else if $subitem.action_type == 'param_url'}
                        <li class="nav-item">
                            <a href="{$site_url}{$subitem.url}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{__($subitem.label)}</p>
                            </a>
                        </li>
                        {/if}
                        {/foreach}
                    </ul>
                </li>
                {/if}
                {/if}
                {/foreach}

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>

</aside>
{/if}
{/if}