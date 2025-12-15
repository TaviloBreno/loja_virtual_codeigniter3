<div class="navbar-bg"></div>

<?php $this->load->view('restrita/layout/navbar'); ?>
<?php $this->load->view('restrita/layout/sidebar'); ?>


<!-- Main Content -->
<div class="main-content">

	<section class="section">
		<div class="section-body">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4><?php echo isset($titulo) ? $titulo : 'Gerenciar Subcategorias'; ?></h4>
							<div class="card-header-action">
								<a href="<?php echo base_url('restrita/subcategorias/core'); ?>" class="btn btn-primary">
									<i class="fas fa-plus"></i> Cadastrar
								</a>
							</div>
						</div>
						<div class="card-body">

							<?php if ($message = $this->session->flashdata('sucesso')): ?>
								<div class="alert alert-success alert-dismissible show fade">
									<div class="alert-body">
										<button class="close" data-dismiss="alert">
											<span>×</span>
										</button>
										<i class="fas fa-check-circle"></i> <?php echo $message; ?>
									</div>
								</div>
							<?php endif; ?>

							<?php if ($message = $this->session->flashdata('error')): ?>
								<div class="alert alert-danger alert-dismissible show fade">
									<div class="alert-body">
										<button class="close" data-dismiss="alert">
											<span>×</span>
										</button>
										<i class="fas fa-times-circle"></i> <?php echo $message; ?>
									</div>
								</div>
							<?php endif; ?>

							<div class="table-responsive">
								<table class="table table-striped data-table" id="table-1">
									<thead>
										<tr>
											<th class="text-center">#</th>
											<th>Subcategoria</th>
											<th>Categoria</th>
											<th>Meta Link</th>
											<th>Ativa</th>
											<th class="text-center">Ações</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($subcategorias as $subcategoria): ?>
											<?php 
												// Buscar nome da categoria pai
												$categoria_pai = $this->core_model->get_by_id('categorias_pai', array('categoria_pai_id' => $subcategoria->categoria_pai_id));
											?>
											<tr>
												<td class="text-center"><?php echo $subcategoria->categoria_id; ?></td>
												<td><?php echo $subcategoria->categoria_nome; ?></td>
												<td><?php echo $categoria_pai ? $categoria_pai->categoria_pai_nome : 'N/A'; ?></td>
												<td><?php echo $subcategoria->categoria_meta_link; ?></td>
												<td>
													<?php if ($subcategoria->categoria_ativa == 1): ?>
														<span class="badge badge-success">Sim</span>
													<?php else: ?>
														<span class="badge badge-danger">Não</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<a href="<?php echo base_url('restrita/subcategorias/core/' . $subcategoria->categoria_id); ?>" class="btn btn-primary btn-sm">
														<i class="fas fa-edit"></i>
													</a>
													<button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<?php echo $subcategoria->categoria_id; ?>" data-nome="<?php echo $subcategoria->categoria_nome; ?>">
														<i class="fas fa-trash"></i>
													</button>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php $this->load->view('restrita/layout/setting-sidebar'); ?>
</div>
