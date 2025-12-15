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
							<h4><i class="fas fa-shipping-fast"></i> Simulador de Frete - Correios</h4>
						</div>
						<div class="card-body">

							<div class="alert alert-info">
								<i class="fas fa-info-circle"></i> Use este simulador para calcular o frete de envio pelos Correios (PAC e SEDEX).
							</div>

							<form id="formFrete">
								<div class="row">
									<div class="col-md-6">
										<h6 class="text-primary mb-3"><i class="fas fa-map-marker-alt"></i> Origem</h6>
										<div class="form-group">
											<label for="cep_origem">CEP de Origem *</label>
											<input type="text" class="form-control" id="cep_origem" name="cep_origem" placeholder="00000-000" maxlength="9">
											<small class="form-text text-muted">CEP de onde o produto será enviado</small>
										</div>
									</div>

									<div class="col-md-6">
										<h6 class="text-primary mb-3"><i class="fas fa-map-marker"></i> Destino</h6>
										<div class="form-group">
											<label for="cep_destino">CEP de Destino *</label>
											<input type="text" class="form-control" id="cep_destino" name="cep_destino" placeholder="00000-000" maxlength="9">
											<small class="form-text text-muted">CEP para onde o produto será enviado</small>
										</div>
									</div>
								</div>

								<hr>

								<h6 class="text-primary mb-3"><i class="fas fa-box"></i> Dimensões do Pacote</h6>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for="peso">Peso (gramas) *</label>
											<input type="number" class="form-control" id="peso" name="peso" value="300" min="1">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="comprimento">Comprimento (cm) *</label>
											<input type="number" class="form-control" id="comprimento" name="comprimento" value="16" min="16" max="105">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="altura">Altura (cm) *</label>
											<input type="number" class="form-control" id="altura" name="altura" value="2" min="2" max="105">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="largura">Largura (cm) *</label>
											<input type="number" class="form-control" id="largura" name="largura" value="11" min="11" max="105">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="valor_declarado">Valor Declarado (R$)</label>
											<input type="text" class="form-control" id="valor_declarado" name="valor_declarado" value="0">
											<small class="form-text text-muted">Para seguro da encomenda</small>
										</div>
									</div>
								</div>

								<button type="submit" class="btn btn-primary btn-lg">
									<i class="fas fa-calculator"></i> Calcular Frete
								</button>
							</form>

							<hr>

							<!-- Resultado -->
							<div id="resultadoFrete" style="display: none;">
								<h5 class="text-success mb-3"><i class="fas fa-check-circle"></i> Resultado do Cálculo</h5>
								<div class="row" id="resultadoCards">
									<!-- Cards com resultados serão inseridos aqui via JavaScript -->
								</div>
							</div>

							<!-- Loading -->
							<div id="loadingFrete" style="display: none;">
								<div class="text-center p-4">
									<div class="spinner-border text-primary" role="status">
										<span class="sr-only">Calculando...</span>
									</div>
									<p class="mt-2">Calculando frete...</p>
								</div>
							</div>

							<!-- Erro -->
							<div id="erroFrete" style="display: none;">
								<div class="alert alert-danger">
									<i class="fas fa-exclamation-triangle"></i> <span id="mensagemErro"></span>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			<!-- Rastreamento -->
			<div class="row mt-4">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4><i class="fas fa-search"></i> Rastrear Encomenda</h4>
						</div>
						<div class="card-body">
							<form id="formRastreio">
								<div class="row">
									<div class="col-md-8">
										<div class="form-group">
											<label for="codigo_rastreio">Código de Rastreio</label>
											<input type="text" class="form-control" id="codigo_rastreio" name="codigo_rastreio" placeholder="AA123456789BR" maxlength="13">
										</div>
									</div>
									<div class="col-md-4">
										<label>&nbsp;</label>
										<button type="submit" class="btn btn-primary btn-block">
											<i class="fas fa-search"></i> Rastrear
										</button>
									</div>
								</div>
							</form>

							<div id="resultadoRastreio" style="display: none;">
								<!-- Resultado do rastreamento -->
							</div>

							<div id="loadingRastreio" style="display: none;">
								<div class="text-center p-4">
									<div class="spinner-border text-primary" role="status">
										<span class="sr-only">Rastreando...</span>
									</div>
									<p class="mt-2">Rastreando encomenda...</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php $this->load->view('restrita/layout/setting-sidebar'); ?>
</div>
