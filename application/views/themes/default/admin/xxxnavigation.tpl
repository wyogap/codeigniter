
{assign var=page_group value=''}

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
				<a href="{site_url('crud/profile')}" class="side-nav-link">
					<i class="dripicons-user"></i>
					<span>{__('Profil')}</span>
				</a>
			</li>

			<li class="side-nav-title side-nav-item">{__('Administrasi')}</li>

			<li class="side-nav-item {if $page_name=='users'}active{/if}">
				<a href="{site_url('crud/users')}" class="side-nav-link">
					<i class="dripicons-user-group"></i>
					<span>{__('Pengguna')}</span>
				</a>
			</li>

			{if in_array($page_name,['pages', 'permissions', 'tables'])}
			{assign var=page_group value='crud'}
			{/if}

			<li class="side-nav-item {if $page_group=='crud'}active{/if}">
				<a href="javascript: void(0);" class="side-nav-link" aria-expanded="false">
					<i class="dripicons-stack"></i>
					<span>CRUD</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level collapse {if $page_group=='crud'}in{/if}" aria-expanded="false">
					<li class="{if $page_name=='pages'}active{/if}">
						<a href="{site_url('crud/pages')}">Halaman</a>
					</li>

					<li class="{if $page_name=='permissions'}active{/if}">
						<a href="{site_url('crud/permissions')}">Hak Akses</a>
					</li>

					<li class="{if $page_name=='tables'}active{/if}">
						<a href="{site_url('crud/tables')}">Tabel</a>
					</li>

					<li class="{if $page_name=='columns'}active{/if}">
						<a href="{site_url('crud/columns')}">Kolom</a>
					</li>
				</ul>
			</li>

			{if in_array($page_name,['roles', 'lookups'])}
			{assign var=page_group value='masterdata'}
			{/if}

			<li class="side-nav-item {if $page_group=='masterdata'}active{/if}">
				<a href="javascript: void(0);" class="side-nav-link" aria-expanded="false">
					<i class="dripicons-view-thumb"></i>
					<span>Master Data</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level collapse {if $page_group=='masterdata'}in{/if}" aria-expanded="false">
					<li class="{if $page_name=='roles'}active{/if}">
						<a href="{site_url('crud/roles')}">Peran</a>
					</li>

					<li class="{if $page_name=='lookups'}active{/if}">
						<a href="{site_url('crud/lookups')}">Lookup</a>
					</li>
				</ul>
			</li>

			{if in_array($page_name,['settings', 'audittrails'])}
			{assign var=page_group value='system'}
			{/if}

			<li class="side-nav-item {if $page_group=='system'}active{/if}">
				<a href="javascript: void(0);" class="side-nav-link" aria-expanded="false">
					<i class="dripicons-gear"></i>
					<span>System</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level collapse {if $page_group=='system'}in{/if}" aria-expanded="false">
					<li class="{if $page_name=='settings'}active{/if}">
						<a href="{site_url('crud/settings')}">Konfigurasi</a>
					</li>

					<li class="{if $page_name=='audittrails'}active{/if}">
						<a href="{site_url('crud/audittrails')}">Audit Trail</a>
					</li>
				</ul>
			</li>
	    </ul>
</div>

