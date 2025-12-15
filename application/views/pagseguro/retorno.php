<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $titulo; ?></title>
	<link rel="stylesheet" href="<?php echo base_url('public/assets/css/app.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('public/assets/bundles/bootstrap-social/bootstrap-social.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('public/assets/css/style.css'); ?>">
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
								<h4><i class="fas fa-check-circle"></i> Pagamento Processado</h4>
							</div>
							<div class="card-body text-center">
								<div class="mb-4">
									<i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
								</div>
								<h5>Obrigado pela sua compra!</h5>
								<p class="text-muted">Seu pagamento está sendo processado.</p>
								
								<?php if ($transaction_id): ?>
									<div class="alert alert-info">
										<strong>ID da Transação:</strong><br>
										<code><?php echo $transaction_id; ?></code>
									</div>
								<?php endif; ?>
								
								<p class="mb-4">Você receberá um e-mail com os detalhes do pedido e as instruções para acompanhamento.</p>
								
								<a href="<?php echo base_url(); ?>" class="btn btn-primary btn-lg btn-block">
									<i class="fas fa-home"></i> Voltar para o Site
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

	<script src="<?php echo base_url('public/assets/js/app.min.js'); ?>"></script>
	<script src="<?php echo base_url('public/assets/js/scripts.js'); ?>"></script>
</body>
</html>
