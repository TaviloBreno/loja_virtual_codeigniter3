<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
	<title><?php echo isset($titulo) ? $titulo . ' - ' : ''; ?>Loja Virtual - Admin</title>
	<!-- General CSS Files -->
	<link rel="stylesheet" href="<?php echo base_url('public/'); ?>assets/css/app.min.css">
	<link rel="stylesheet" href="<?php echo base_url('public/'); ?>assets/bundles/bootstrap-social/bootstrap-social.css">
	<!-- Template CSS -->
	<link rel="stylesheet" href="<?php echo base_url('public/'); ?>assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url('public/'); ?>assets/css/components.css">
	<!-- Custom style CSS -->
	<link rel="stylesheet" href="<?php echo base_url('public/'); ?>assets/css/custom.css">
	<link rel="stylesheet" href="<?php echo base_url('public/'); ?>assets/css/login.css">
	<link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url('public/'); ?>assets/img/favicon.ico' />
</head>

<body>
	<div class="loader"></div>
	<div id="app">
		<section class="section">
			<div class="container mt-5">
				<div class="row">
					<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
						<div class="card card-primary">
							<div class="card-header">
								<h4>Login - Área Administrativa</h4>
							</div>

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

								<?php echo form_open('login', array('id' => 'form_login')); ?>

								<div class="form-group">
									<label for="email">E-mail ou Usuário</label>
									<input id="email" type="text" class="form-control" name="email" tabindex="1" autofocus value="<?php echo set_value('email'); ?>">
									<?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>
								</div>

								<div class="form-group">
									<div class="d-block">
										<label for="password" class="control-label">Senha</label>
										<div class="float-right">
											<a href="<?php echo base_url('login/esqueci_senha'); ?>" class="text-small">
												Esqueceu a senha?
											</a>
										</div>
									</div>
									<input id="password" type="password" class="form-control" name="password" tabindex="2">
									<?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
								</div>

								<div class="form-group">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me" value="1">
										<label class="custom-control-label" for="remember-me">Lembrar-me</label>
									</div>
								</div>

								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
										<i class="fas fa-sign-in-alt"></i> Entrar
									</button>
								</div>

								<?php echo form_close(); ?>

							</div>
						</div>
						<div class="simple-footer">
							Copyright &copy; <?php echo date('Y'); ?> Loja Virtual
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<!-- General JS Scripts -->
	<script src="<?php echo base_url('public/'); ?>assets/js/app.min.js"></script>
	<!-- JS Libraies -->
	<!-- Page Specific JS File -->
	<!-- Template JS File -->
	<script src="<?php echo base_url('public/'); ?>assets/js/scripts.js"></script>
	<!-- Custom JS File -->
	<script src="<?php echo base_url('public/'); ?>assets/js/custom.js"></script>
	<!-- Alerts Auto-hide -->
	<script src="<?php echo base_url('public/'); ?>assets/js/alerts.js"></script>
	<!-- Login JS -->
	<script src="<?php echo base_url('public/'); ?>assets/js/login.js"></script>
</body>

</html>
