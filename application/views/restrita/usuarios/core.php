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


						<form name="form_core" method="POST" action="<?php echo base_url('restrita/usuarios/core/' . $usuario->id); ?>">

							<div class="card-body">
								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="inputNome">Nome</label>
										<input type="text" class="form-control" name="first_name" id="inputNome" value="<?php echo isset($usuario) ? $usuario->first_name : ''; ?>">
									</div>
									<div class="form-group col-md-6">
										<label for="inputSobrenome">Sobrenome</label>
										<input type="text" class="form-control" name="last_name" id="inputSobrenome" value="<?php echo isset($usuario) ? $usuario->last_name : ''; ?>">
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="inputEmail">Email</label>
										<input type="email" class="form-control" name="email" id="inputEmail" value="<?php echo isset($usuario) ? $usuario->email : ''; ?>">
									</div>
									<div class="form-group col-md-6">
										<label for="inputUsuario">Usuário</label>
										<input type="text" class="form-control" name="username" id="inputUsuario" value="<?php echo isset($usuario) ? $usuario->username : ''; ?>">
									</div>
								</div>

								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="inputAtivo">Ativo</label>
										<select class="form-control" name="active" id="inputAtivo">
											<option value="1" <?php echo (isset($usuario) && $usuario->active == 1) ? 'selected' : ''; ?>>Sim</option>
											<option value="0" <?php echo (isset($usuario) && $usuario->active == 0) ? 'selected' : ''; ?>>Não</option>
										</select>
									</div>
									<div class="form-group col-md-6">
										<label for="inputPerfil">Perfil</label>
										<select class="form-control" name="perfil" id="inputPerfil">
											<?php foreach ($grupos as $grupo): ?>

												<option value="<?php echo $grupo->id; ?>" <?php echo (isset($perfil) && $perfil->id == $grupo->id) ? 'selected' : ''; ?>><?php echo $grupo->name; ?></option>

											<?php endforeach; ?>
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
								<a href="<?php echo base_url('restrita/usuarios'); ?>" class="btn btn-secondary ml-2"><i class="fas fa-arrow-left"></i> Voltar</a>
							</div>

						</form>

					</div>
				</div>
			</div>
		</div>
</div>
</section>

<?php $this->load->view('restrita/layout/setting-sidebar'); ?>
</div>
