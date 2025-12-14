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
							<h4><?php echo isset($titulo) ? $titulo : 'Gerenciar Sistema'; ?></h4>
						</div>

						<?php echo form_open_multipart('restrita/sistema/'); ?>

						<div class="card-body">

							<?php if ($message = $this->session->flashdata('error')): ?>
								<div class="alert alert-danger alert-has-icon alert-dismissible show fade">
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
								<div class="alert alert-success alert-has-icon alert-dismissible show fade">
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

							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputRazaoSocial">Razão Social</label>
								<input type="text" class="form-control" name="sistema_razao_social" id="inputRazaoSocial" value="<?php echo isset($sistema) ? $sistema->sistema_razao_social : ''; ?>">
								<?php echo form_error('sistema_razao_social', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-6">
									<label for="inputNomeFantasia">Nome Fantasia</label>
								<input type="text" class="form-control" name="sistema_nome_fantasia" id="inputNomeFantasia" value="<?php echo isset($sistema) ? $sistema->sistema_nome_fantasia : ''; ?>">
								<?php echo form_error('sistema_nome_fantasia', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputCNPJ">CNPJ</label>
								<input type="text" class="form-control cnpj" name="sistema_cnpj" id="inputCNPJ" value="<?php echo isset($sistema) ? $sistema->sistema_cnpj : ''; ?>">
								<?php echo form_error('sistema_cnpj', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-6">
									<label for="inputInscricaoEstadual">Inscrição Estadual</label>
								<input type="text" class="form-control" name="sistema_ie" id="inputInscricaoEstadual" value="<?php echo isset($sistema) ? $sistema->sistema_ie : ''; ?>">
								<?php echo form_error('sistema_ie', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputTelefoneFixo">Telefone Fixo</label>
								<input type="text" class="form-control phone_with_ddd" name="sistema_telefone_fixo" id="inputTelefoneFixo" value="<?php echo isset($sistema) ? $sistema->sistema_telefone_fixo : ''; ?>">
								<?php echo form_error('sistema_telefone_fixo', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-6">
									<label for="inputTelefoneMovel">Telefone Móvel</label>
								<input type="text" class="form-control phone_with_ddd" name="sistema_telefone_movel" id="inputTelefoneMovel" value="<?php echo isset($sistema) ? $sistema->sistema_telefone_movel : ''; ?>">
								<?php echo form_error('sistema_telefone_movel', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputEmail">E-mail</label>
								<input type="email" class="form-control" name="sistema_email" id="inputEmail" value="<?php echo isset($sistema) ? $sistema->sistema_email : ''; ?>">
								<?php echo form_error('sistema_email', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-6">
									<label for="inputSiteUrl">URL do Site</label>
								<input type="text" class="form-control" name="sistema_site_url" id="inputSiteUrl" value="<?php echo isset($sistema) ? $sistema->sistema_site_url : ''; ?>">
								<?php echo form_error('sistema_site_url', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputCidade">Cidade</label>
								<input type="text" class="form-control" name="sistema_cidade" id="inputCidade" value="<?php echo isset($sistema) ? $sistema->sistema_cidade : ''; ?>">
								<?php echo form_error('sistema_cidade', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-6">
									<label for="inputNumero">Número</label>
								<input type="text" class="form-control" name="sistema_numero" id="inputNumero" value="<?php echo isset($sistema) ? $sistema->sistema_numero : ''; ?>">
								<?php echo form_error('sistema_numero', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputEndereco">Endereço</label>
								<input type="text" class="form-control" name="sistema_endereco" id="inputEndereco" value="<?php echo isset($sistema) ? $sistema->sistema_endereco : ''; ?>">
								<?php echo form_error('sistema_endereco', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-6">
									<label for="inputCep">CEP</label>
								<input type="text" class="form-control cep" name="sistema_cep" id="inputCep" value="<?php echo isset($sistema) ? $sistema->sistema_cep : ''; ?>">
								<?php echo form_error('sistema_cep', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputEstado">Estado</label>
								<input type="text" class="form-control" name="sistema_estado" id="inputEstado" value="<?php echo isset($sistema) ? $sistema->sistema_estado : ''; ?>">
								<?php echo form_error('sistema_estado', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-6">
									<label for="inputProdutosDestaques">Quantidade de produtos em destaque</label>
							<input type="number" class="form-control" name="sistema_produtos_destaques" id="inputProdutosDestaques" value="<?php echo isset($sistema) ? $sistema->sistema_produtos_destaques : ''; ?>">
							<?php echo form_error('sistema_produtos_destaques', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="inputTexto">Texto</label>
									<textarea class="form-control" name="sistema_texto" id="inputTexto" rows="3"><?php echo isset($sistema) ? $sistema->sistema_texto : ''; ?></textarea>
									<?php echo form_error('sistema_texto', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>
						</div>

						<div class="card-footer">
							<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
						</div>

						<?php echo form_close(); ?>

					</div>
				</div>
			</div>
		</div>
</div>
</section>

<?php $this->load->view('restrita/layout/setting-sidebar'); ?>
</div>
