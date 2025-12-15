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
							<h4><i class="fas fa-cog"></i> Configurações PagSeguro</h4>
							<div class="card-header-action">
								<a href="<?php echo base_url('restrita/pagamentos'); ?>" class="btn btn-secondary">
									<i class="fas fa-arrow-left"></i> Voltar
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

							<div class="alert alert-warning">
								<i class="fas fa-exclamation-triangle"></i> <strong>Importante:</strong> Para obter suas credenciais do PagSeguro, acesse: 
								<a href="https://pagseguro.uol.com.br/" target="_blank">pagseguro.uol.com.br</a>
							</div>

							<form method="post">

								<div class="form-group">
									<label for="sistema_pagseguro_email">E-mail PagSeguro *</label>
									<input type="email" class="form-control" id="sistema_pagseguro_email" name="sistema_pagseguro_email" value="<?php echo isset($sistema->sistema_pagseguro_email) ? $sistema->sistema_pagseguro_email : ''; ?>" required>
									<small class="form-text text-muted">E-mail da sua conta PagSeguro</small>
								</div>

								<div class="form-group">
									<label for="sistema_pagseguro_token">Token de Segurança *</label>
									<input type="text" class="form-control" id="sistema_pagseguro_token" name="sistema_pagseguro_token" value="<?php echo isset($sistema->sistema_pagseguro_token) ? $sistema->sistema_pagseguro_token : ''; ?>" required>
									<small class="form-text text-muted">Token gerado no painel do PagSeguro (mínimo 32 caracteres)</small>
								</div>

								<div class="form-group">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="sistema_pagseguro_sandbox" name="sistema_pagseguro_sandbox" value="1" <?php echo (isset($sistema->sistema_pagseguro_sandbox) && $sistema->sistema_pagseguro_sandbox == 1) ? 'checked' : ''; ?>>
										<label class="custom-control-label" for="sistema_pagseguro_sandbox">
											Modo Sandbox (Ambiente de Testes)
										</label>
									</div>
									<small class="form-text text-muted">Ative para testar sem realizar cobranças reais</small>
								</div>

								<hr>

								<h6 class="text-primary mb-3">URLs de Notificação</h6>
								<div class="alert alert-info">
									<p><strong>URL de Notificação:</strong><br>
									<code><?php echo base_url('pagseguro/notificacao'); ?></code></p>
									<p class="mb-0"><strong>URL de Retorno:</strong><br>
									<code><?php echo base_url('pagseguro/retorno'); ?></code></p>
									<small>Configure estas URLs no painel do PagSeguro para receber notificações de pagamento.</small>
								</div>

								<button type="submit" class="btn btn-primary btn-lg">
									<i class="fas fa-save"></i> Salvar Configurações
								</button>

							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php $this->load->view('restrita/layout/setting-sidebar'); ?>
</div>
