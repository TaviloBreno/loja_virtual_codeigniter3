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
							<h4><i class="fas fa-credit-card"></i> Transações de Pagamento</h4>
							<div class="card-header-action">
								<a href="<?php echo base_url('restrita/pagamentos/testar_checkout'); ?>" class="btn btn-primary">
									<i class="fas fa-shopping-cart"></i> Testar Checkout
								</a>
								<a href="<?php echo base_url('restrita/pagamentos/configuracoes'); ?>" class="btn btn-warning">
									<i class="fas fa-cog"></i> Configurações
								</a>
							</div>
						</div>
						<div class="card-body">

							<?php if ($message = $this->session->flashdata('sucesso')): ?>
								<div class="alert alert-success alert-dismissible show fade">
									<div class="alert-body">
										<button class="close" data-dismiss="alert"><span>&times;</span></button>
										<?php echo $message; ?>
									</div>
								</div>
							<?php endif; ?>

							<?php if ($message = $this->session->flashdata('erro')): ?>
								<div class="alert alert-danger alert-dismissible show fade">
									<div class="alert-body">
										<button class="close" data-dismiss="alert"><span>&times;</span></button>
										<?php echo $message; ?>
									</div>
								</div>
							<?php endif; ?>

							<?php if ($transacoes): ?>
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th class="d-none d-md-table-cell">#</th>
												<th>Código</th>
												<th class="d-none d-lg-table-cell">Referência</th>
												<th>Status</th>
												<th class="d-none d-xl-table-cell">Método</th>
												<th>Valor</th>
												<th class="d-none d-md-table-cell">Data</th>
												<th style="min-width: 100px;">Ações</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($transacoes as $transacao): ?>
												<tr>
													<td class="d-none d-md-table-cell"><?php echo $transacao->transacao_id; ?></td>
													<td><?php echo $transacao->transacao_codigo; ?></td>
													<td class="d-none d-lg-table-cell"><?php echo $transacao->transacao_referencia; ?></td>
													<td>
														<?php
														$status_class = '';
														switch ($transacao->transacao_status) {
															case 1:
																$status_class = 'warning';
																$status_text = 'Aguardando';
																break;
															case 2:
																$status_class = 'info';
																$status_text = 'Em análise';
																break;
															case 3:
																$status_class = 'success';
																$status_text = 'Paga';
																break;
															case 4:
																$status_class = 'success';
																$status_text = 'Disponível';
																break;
															case 5:
																$status_class = 'danger';
																$status_text = 'Em disputa';
																break;
															case 6:
																$status_class = 'danger';
																$status_text = 'Devolvida';
																break;
															case 7:
																$status_class = 'danger';
																$status_text = 'Cancelada';
																break;
															default:
																$status_class = 'secondary';
																$status_text = 'Desconhecido';
														}
														?>
														<span class="badge badge-<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
													</td>
													<td class="d-none d-xl-table-cell"><?php echo $transacao->transacao_metodo_pagamento; ?></td>
													<td>R$ <?php echo number_format($transacao->transacao_valor_bruto, 2, ',', '.'); ?></td>
													<td class="d-none d-md-table-cell"><?php echo date('d/m/Y H:i', strtotime($transacao->transacao_data_cadastro)); ?></td>
													<td>
														<button class="btn btn-sm btn-info btn-consultar" data-code="<?php echo $transacao->transacao_codigo; ?>">
															<i class="fas fa-sync"></i> <span class="d-none d-lg-inline">Atualizar</span>
														</button>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							<?php else: ?>
								<div class="alert alert-info">
									<i class="fas fa-info-circle"></i> Nenhuma transação registrada ainda.
								</div>
							<?php endif; ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php $this->load->view('restrita/layout/setting-sidebar'); ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Consultar transação
	document.querySelectorAll('.btn-consultar').forEach(function(btn) {
		btn.addEventListener('click', function() {
			const code = this.dataset.code;
			const protocol = window.location.protocol;
			const host = window.location.host;
			const pathname = window.location.pathname;
			
			let baseUrl;
			if (pathname.includes('index.php')) {
				const pathParts = pathname.split('index.php')[0];
				baseUrl = protocol + '//' + host + pathParts + 'index.php/restrita/pagamentos/consultar_transacao';
			} else {
				const pathParts = pathname.split('/restrita')[0];
				baseUrl = protocol + '//' + host + pathParts + '/restrita/pagamentos/consultar_transacao';
			}
			
			const formData = new FormData();
			formData.append('transaction_code', code);
			
			fetch(baseUrl, {
				method: 'POST',
				body: formData
			})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					alert('Status: ' + data.data.status_descricao);
					location.reload();
				} else {
					alert('Erro: ' + data.message);
				}
			});
		});
	});
});
</script>
