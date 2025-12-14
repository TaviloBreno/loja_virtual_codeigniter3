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
							<h4><?php echo isset($titulo) ? $titulo : 'Gerenciar Marcas'; ?></h4>
							<div class="card-header-action">
								<a href="<?php echo base_url('restrita/marcas/core'); ?>" class="btn btn-primary btn-sm">Cadastrar</a>
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
											<th class="text-center">
												#
											</th>
											<th>Marca</th>
											<th>Meta link</th>
											<th>Ativa</th>
											<th class="nosort">Ações</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($marcas as $marca): ?>

											<tr>
												<td class="text-center"><?php echo $marca->marca_id; ?></td>
												<td><?php echo $marca->marca_nome; ?></td>
												<td><?php echo $marca->marca_meta_link; ?></td>
												<td><?php echo ($marca->marca_ativa == 1 ? '<span class="badge badge-success badge-shadow">Sim</span>' : '<span class="badge badge-danger badge-shadow">Não</span>'); ?></td>
												<td>
													<a href="<?php echo base_url('restrita/marcas/core/' . $marca->marca_id); ?>" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="Editar"><i class="fas fa-pencil-alt"></i></a>
													<a href="<?php echo base_url('restrita/marcas/delete/' . $marca->marca_id); ?>" class="btn btn-danger btn-action btn-delete" data-toggle="tooltip" title="Excluir"><i class="fas fa-trash"></i></a>
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
