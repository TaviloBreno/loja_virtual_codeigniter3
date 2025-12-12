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
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputNome">Nome</label>
									<input type="text" class="form-control" name="first_name" id="inputNome" placeholder="Nome">
								</div>
								<div class="form-group col-md-6">
									<label for="inputSobrenome">Sobrenome</label>
									<input type="text" class="form-control" name="last_name" id="inputSobrenome" placeholder="Sobrenome">
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputEmail">Email</label>
									<input type="email" class="form-control" name="email" id="inputEmail" placeholder="Email">
								</div>
								<div class="form-group col-md-6">
									<label for="inputUsuario">Usuário</label>
									<input type="text" class="form-control" name="username" id="inputUsuario" placeholder="Usuário">
								</div>
							</div>

							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputAtivo">Status</label>
									<select class="form-control" name="active" id="inputAtivo">
										<?php if($usuario->active == 1): ?>
											<option value="1" selected>Ativo</option>
											<option value="0">Inativo</option>
										<?php else: ?>
											<option value="1">Ativo</option>
											<option value="0" selected>Inativo</option>
										<?php endif; ?>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label for="inputPerfil">Perfil</label>
									<select class="form-control" name="perfil" id="inputPerfil">
										<?php if($this->ion_auth->is_admin($usuario->id)): ?>
											<option value="admin" selected>Administrador</option>
											<option value="cliente">Cliente</option>
										<?php else: ?>
											<option value="admin">Administrador</option>
											<option value="cliente" selected>Cliente</option>
										<?php endif; ?>
									</select>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputSenha">Senha</label>
									<input type="password" class="form-control" name="password" id="inputSenha" placeholder="Senha">
								</div>
								<div class="form-group col-md-6">
									<label for="inputConfirmaSenha">Confirmação de senha</label>
									<input type="password" class="form-control" name="confirm_password" id="inputConfirmaSenha" placeholder="Confirmação de senha">
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button class="btn btn-primary"><i class="fas fa-edit"></i> Editar</button>
							<button class="btn btn-secondary ml-2"><i class="fas fa-arrow-left"></i> Voltar</button>
						</div>

					</div>
				</div>
			</div>
		</div>
</div>
</section>

<?php $this->load->view('restrita/layout/setting-sidebar'); ?>
</div>
