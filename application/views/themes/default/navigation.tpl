{if !empty($navigation)} 
{if $navigation|@count > 0}
<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu left-side-menu-detached">
	<div class="leftbar-user">
		<a href="{site_url()}/user/profile">
			<img src="{$userdata.profile_img}" alt="user-image" height="42" class="rounded-circle shadow-sm">
			<span class="leftbar-user-name">{$userdata.nama}</span>
		</a>
	</div>

	<!--- Sidemenu -->
		<ul class="metismenu side-nav side-nav-light">

			{if !empty($level1_title)}
                <li class="side-nav-title side-nav-item">{$level1_title}</li>
            {/if}

			{foreach $navigation as $nav}
			{if $nav.nav_type == 'title'}
				<li class="side-nav-title side-nav-item">{__($nav.label)}</li>
			{else if $nav.nav_type == 'item'}
				{if $nav.action_type == 'page'}
				<li class="side-nav-item {if $page_name==$nav.page_name}active{/if}">
					<a href="{site_url()}/{$controller}/{$nav.page_name}{if !empty($nav.page_param)}{$nav.page_param}{/if}" class="side-nav-link">
						<i class="{$nav.icon}"></i>
						<span>{__($nav.label)}</span>
					</a>
				</li>
				{else if $nav.action_type == 'url'}
				<li class="side-nav-item">
					<a href="{$nav.url}" class="side-nav-link">
						<i class="{$nav.icon}"></i>
						<span>{__($nav.label)}</span>
					</a>
				</li>
				{else if $nav.action_type == 'param_url'}
				<li class="side-nav-item">
					<a href="{site_url()}/{$nav.url}" class="side-nav-link">
						<i class="{$nav.icon}"></i>
						<span>{__($nav.label)}</span>
					</a>
				</li>
				{else if $nav.action_type == 'dropdown'}
					{assign var=page_selected value='0'}
					{if in_array($page_name,$nav.pages)}
						{assign var=page_selected value='1'}
					{/if}

				<li class="side-nav-item {if $page_selected==1}active{/if}">
					<a href="javascript: void(0);" class="side-nav-link" 
					{if $page_selected==1}aria-expanded="true"{/if}>
						<i class="{$nav.icon}"></i>
						<span>{__($nav.label)}</span>
						<span class="menu-arrow"></span>
					</a>

					<ul class="side-nav-second-level collapse {if $page_selected==1}in{/if}" aria-expanded="false">
						{foreach $nav.subitems as $subitem}
						{if $subitem.action_type == 'page'}
						<li class="{if $page_name=='{$subitem.page_name}'}active{/if}">
							<a href="{site_url()}/{$controller}/{$subitem.page_name}{if !empty($nav.page_param)}{$nav.page_param}{/if}">{__($subitem.label)}</a>
						</li>
						{else if $subitem.action_type == 'url'}
						<li class="">
							<a href="{$subitem.url}">{__($subitem.label)}</a>
						</li>
						{else if $subitem.action_type == 'param_url'}
						<li class="">
							<a href="{site_url()}/{$subitem.url}">{__($subitem.label)}</a>
						</li>
						{/if}
						{/foreach}
					</ul>
				</li>
				{/if}
			{/if}
			{/foreach}
    	</ul>
</div>
{/if}
{/if}