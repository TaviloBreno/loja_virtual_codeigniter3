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
							<h4><?php echo isset($titulo) ? $titulo : 'Gerenciar Usuários'; ?></h4>
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
											<th>Nome completo</th>
											<th>E-mail</th>
											<th>Usuario</th>
											<th>Status</th>
											<th class="nosort">Ações</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($usuarios as $usuario): ?>

											<tr>
												<td class="text-center"><?php echo $usuario->id; ?></td>
												<td><?php echo $usuario->first_name . ' ' . $usuario->last_name; ?></td>
												<td><?php echo $usuario->email; ?></td>
												<td><?php echo $usuario->username; ?></td>
												<td><?php echo ($usuario->active == 1 ? '<span class="badge badge-success badge-shadow">Ativo</span>' : '<span class="badge badge-danger badge-shadow">Inativo</span>'); ?></td>
												<td>
													<a href="<?php echo base_url('restrita/usuarios/core/' . $usuario->id); ?>" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="Editar"><i class="fas fa-pencil-alt"></i></a>
													<a href="<?php echo base_url('restrita/usuarios/delete/' . $usuario->id); ?>" class="btn btn-danger btn-action" data-toggle="tooltip" title="Excluir"><i class="fas fa-trash"></i></a>
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
