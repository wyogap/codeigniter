 

<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu left-side-menu-detached">
	<div class="leftbar-user">
		<a href="javascript: void(0);">
			<img src="{$userdata.profile_img}" alt="user-image" height="42" class="rounded-circle shadow-sm">
			<span class="leftbar-user-name">{$userdata.nama}</span>
		</a>
	</div>

	<!--- Sidemenu -->
		<ul class="metismenu side-nav side-nav-light">

			{foreach $navigation as $nav}
			{if $nav.nav_type == 'title'}
				<li class="side-nav-title side-nav-item">{__($nav.label)}</li>
			{else if $nav.nav_type == 'item'}
				{if $nav.action_type == 'page'}
				<li class="side-nav-item {if $page_name==$nav.page_name}active{/if}">
					<a href="{base_url('crud')}/{$nav.page_name}" class="side-nav-link">
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
						<li class="{if $page_name=='{$subitem.page_name}'}active{/if}">
							<a href="{base_url('crud')}/{$subitem.page_name}">{__($subitem.label)}</a>
						</li>
						{/foreach}
					</ul>
				</li>
				{/if}
			{/if}
			{/foreach}
    	</ul>
</div>

