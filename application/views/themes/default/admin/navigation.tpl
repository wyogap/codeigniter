
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

			<li class="side-nav-item {if $page_name=='profile'}active{/if}">
				<a href="{base_url('user/profile')}" class="side-nav-link">
					<i class="dripicons-user"></i>
					<span>{__('My Profile')}</span>
				</a>
			</li>

	    </ul>
</div>

