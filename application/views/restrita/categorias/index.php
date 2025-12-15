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
							<h4><?php echo isset($titulo) ? $titulo : 'Gerenciar Categorias'; ?></h4>
							<div class="card-header-action">
								<a href="<?php echo base_url('restrita/categorias/core'); ?>" class="btn btn-primary btn-sm">Cadastrar</a>
							</div>
						</div>
						<div class="card-body">

							<?php if ($message = $this->session->flashdata('error')): ?>
								<div class="alert alert-danger alert-has-icon alert-dismissible">
									<div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
									<div class="alert-body">
										<div class="alert-title">Atenção!</div>
										<?php echo $message; ?>
									</div>
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
							<?php endif; ?>

							<?php if ($message = $this->session->flashdata('sucesso')): ?>
								<div class="alert alert-success alert-has-icon alert-dismissible">
									<div class="alert-icon"><i class="fas fa-check-circle"></i></div>
									<div class="alert-body">
										<div class="alert-title">Sucesso!</div>
										<?php echo $message; ?>
									</div>
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
							<?php endif; ?>

							<div class="table-responsive">
								<table class="table table-striped data-table">
									<thead>
										<tr>
											<th class="text-center d-none d-md-table-cell">
												#
											</th>
											<th>Categoria</th>
											<th class="d-none d-lg-table-cell">Meta link</th>
											<th class="d-none d-md-table-cell">Ativa</th>
											<th class="nosort" style="min-width: 100px;">Ações</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($categorias as $categoria): ?>

											<tr>
											<td class="text-center d-none d-md-table-cell"><?php echo $categoria->categoria_pai_id; ?></td>
											<td><?php echo $categoria->categoria_pai_nome; ?></td>
											<td class="d-none d-lg-table-cell"><?php echo $categoria->categoria_pai_meta_link; ?></td>
											<td class="d-none d-md-table-cell"><?php echo ($categoria->categoria_pai_ativa == 1 ? '<span class="badge badge-success badge-shadow">Sim</span>' : '<span class="badge badge-danger badge-shadow">Não</span>'); ?></td>
											<td class="text-nowrap">
													<button type="button" class="btn btn-danger btn-action btn-delete" data-toggle="tooltip" title="Excluir" data-id="<?php echo $categoria->categoria_pai_id; ?>" data-nome="<?php echo $categoria->categoria_pai_nome; ?>"><i class="fas fa-trash"></i></button>
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
