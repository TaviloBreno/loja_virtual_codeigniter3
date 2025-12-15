<div class="main-sidebar sidebar-style-2">
				<aside id="sidebar-wrapper">
					<div class="sidebar-brand">
						<a href="index.html"> <img alt="image" src="<?php echo base_url('public/'); ?>assets/img/logo.png" class="header-logo" /> <span
								class="logo-name">Otika</span>
						</a>
					</div>
					<ul class="sidebar-menu">
						<li class="menu-header">Main</li>
						<li class="dropdown <?php echo $this->router->fetch_class() == 'home' ? 'active' : ''; ?>">
							<a href="<?php echo base_url('restrita'); ?>" class="nav-link"><i data-feather="monitor"></i><span>Home</span></a>
						</li>
						<li class="dropdown <?php echo $this->router->fetch_class() == 'usuarios' ? 'active' : ''; ?>">
							<a href="<?php echo base_url('restrita/usuarios'); ?>" class="nav-link"><i data-feather="users"></i><span>Usuários</span></a>
						</li>
						<li class="dropdown <?php echo $this->router->fetch_class() == 'sistema' ? 'active' : ''; ?>">
							<a href="<?php echo base_url('restrita/sistema'); ?>" class="nav-link"><i data-feather="settings"></i><span>Sistema</span></a>
						</li>
						<li class="dropdown <?php echo $this->router->fetch_class() == 'marcas' ? 'active' : ''; ?>">
							<a href="<?php echo base_url('restrita/marcas'); ?>" class="nav-link"><i data-feather="tag"></i><span>Marcas</span></a>
						</li>
						<li class="dropdown <?php echo $this->router->fetch_class() == 'categorias' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('restrita/categorias'); ?>" class="nav-link"><i data-feather="layers"></i><span>Categorias</span></a>
						</li>
						<li class="dropdown <?php echo $this->router->fetch_class() == 'subcategorias' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('restrita/subcategorias'); ?>" class="nav-link"><i data-feather="list"></i><span>Subcategorias</span></a>
						</li>
						<li class="dropdown <?php echo $this->router->fetch_class() == 'produtos' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('restrita/produtos'); ?>" class="nav-link"><i data-feather="shopping-bag"></i><span>Produtos</span></a>
						</li>
						<li class="dropdown <?php echo $this->router->fetch_class() == 'frete' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('restrita/frete'); ?>" class="nav-link"><i data-feather="truck"></i><span>Simulador de Frete</span></a>
						</li>
						<li class="dropdown <?php echo $this->router->fetch_class() == 'pagamentos' ? 'active' : ''; ?>">
						<a href="<?php echo base_url('restrita/pagamentos'); ?>" class="nav-link"><i data-feather="credit-card"></i><span>Pagamentos</span></a>
						</li>
					</ul>
				</aside>
			</div>
