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
							<h4><?php echo isset($titulo) ? $titulo : 'Gerenciar Produtos'; ?></h4>
						</div>

						<?php $atributos = array(
							'name' => 'form_core',
						);

						if (isset($produto)) {
							$produto_id = $produto->produto_id;
						} else {
							$produto_id = '';
						}
						?>

						<?php echo form_open_multipart('restrita/produtos/core/' . $produto_id, $atributos); ?>

						<div class="card-body">
							
							<!-- Informações Básicas -->
							<h6 class="text-primary mb-3"><i class="fas fa-info-circle"></i> Informações Básicas</h6>
							<div class="form-row">
								<div class="form-group col-md-4">
									<label for="inputProdutoCodigo">Código do produto *</label>
									<input type="text" class="form-control" name="produto_codigo" id="inputProdutoCodigo" value="<?php echo isset($produto) ? $produto->produto_codigo : ''; ?>">
									<?php echo form_error('produto_codigo', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-8">
									<label for="inputProdutoNome">Nome do produto *</label>
									<input type="text" class="form-control" name="produto_nome" id="inputProdutoNome" value="<?php echo isset($produto) ? $produto->produto_nome : ''; ?>">
									<?php echo form_error('produto_nome', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="inputProdutoMetaLink">Meta link</label>
									<input type="text" class="form-control" name="produto_meta_link" id="inputProdutoMetaLink" value="<?php echo isset($produto) ? $produto->produto_meta_link : ''; ?>" readonly>
									<?php echo form_error('produto_meta_link', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<hr>

							<!-- Classificação -->
							<h6 class="text-primary mb-3"><i class="fas fa-tags"></i> Classificação</h6>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputCategoria">Categoria *</label>
									<select class="form-control" name="produto_categoria_id" id="inputCategoria">
										<option value="">Selecione a categoria</option>
										<?php foreach ($categorias as $categoria): ?>
											<option value="<?php echo $categoria->categoria_id; ?>" 
												<?php echo (isset($produto) && $produto->produto_categoria_id == $categoria->categoria_id) ? 'selected' : ''; ?>>
												<?php echo $categoria->categoria_nome; ?>
											</option>
										<?php endforeach; ?>
									</select>
									<?php echo form_error('produto_categoria_id', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-6">
									<label for="inputMarca">Marca *</label>
									<select class="form-control" name="produto_marca_id" id="inputMarca">
										<option value="">Selecione a marca</option>
										<?php foreach ($marcas as $marca): ?>
											<option value="<?php echo $marca->marca_id; ?>" 
												<?php echo (isset($produto) && $produto->produto_marca_id == $marca->marca_id) ? 'selected' : ''; ?>>
												<?php echo $marca->marca_nome; ?>
											</option>
										<?php endforeach; ?>
									</select>
									<?php echo form_error('produto_marca_id', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<hr>

							<!-- Dimensões e Peso -->
							<h6 class="text-primary mb-3"><i class="fas fa-box"></i> Dimensões e Peso (em cm e gramas)</h6>
							<div class="form-row">
								<div class="form-group col-md-3">
									<label for="inputPeso">Peso (g)</label>
									<input type="number" class="form-control" name="produto_peso" id="inputPeso" value="<?php echo isset($produto) ? $produto->produto_peso : ''; ?>">
									<?php echo form_error('produto_peso', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-3">
									<label for="inputAltura">Altura (cm)</label>
									<input type="number" class="form-control" name="produto_altura" id="inputAltura" value="<?php echo isset($produto) ? $produto->produto_altura : ''; ?>">
									<?php echo form_error('produto_altura', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-3">
									<label for="inputLargura">Largura (cm)</label>
									<input type="number" class="form-control" name="produto_largura" id="inputLargura" value="<?php echo isset($produto) ? $produto->produto_largura : ''; ?>">
									<?php echo form_error('produto_largura', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-3">
									<label for="inputComprimento">Comprimento (cm)</label>
									<input type="number" class="form-control" name="produto_comprimento" id="inputComprimento" value="<?php echo isset($produto) ? $produto->produto_comprimento : ''; ?>">
									<?php echo form_error('produto_comprimento', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<hr>

							<!-- Preço e Estoque -->
							<h6 class="text-primary mb-3"><i class="fas fa-dollar-sign"></i> Preço e Estoque</h6>
							<div class="form-row">
								<div class="form-group col-md-4">
									<label for="inputValor">Valor (R$) *</label>
									<input type="text" class="form-control" name="produto_valor" id="inputValor" value="<?php echo isset($produto) ? $produto->produto_valor : ''; ?>">
									<?php echo form_error('produto_valor', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-4">
									<label for="inputControlarEstoque">Controlar estoque *</label>
									<select class="form-control" name="produto_controlar_estoque" id="inputControlarEstoque">
										<option value="1" <?php echo (isset($produto) && $produto->produto_controlar_estoque == 1) ? 'selected' : ''; ?>>Sim</option>
										<option value="0" <?php echo (isset($produto) && $produto->produto_controlar_estoque == 0) ? 'selected' : ''; ?>>Não</option>
									</select>
									<?php echo form_error('produto_controlar_estoque', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-4">
									<label for="inputQuantidadeEstoque">Quantidade em estoque</label>
									<input type="number" class="form-control" name="produto_quantidade_estoque" id="inputQuantidadeEstoque" value="<?php echo isset($produto) ? $produto->produto_quantidade_estoque : '0'; ?>">
									<?php echo form_error('produto_quantidade_estoque', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<hr>

							<!-- Configurações -->
							<h6 class="text-primary mb-3"><i class="fas fa-cog"></i> Configurações</h6>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputDestaque">Produto em destaque *</label>
									<select class="form-control" name="produto_destaque" id="inputDestaque">
										<option value="1" <?php echo (isset($produto) && $produto->produto_destaque == 1) ? 'selected' : ''; ?>>Sim</option>
										<option value="0" <?php echo (isset($produto) && $produto->produto_destaque == 0) ? 'selected' : ''; ?>>Não</option>
									</select>
									<?php echo form_error('produto_destaque', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-6">
									<label for="inputProdutoAtivo">Produto ativo *</label>
									<select class="form-control" name="produto_ativo" id="inputProdutoAtivo">
										<option value="1" <?php echo (isset($produto) && $produto->produto_ativo == 1) ? 'selected' : ''; ?>>Sim</option>
										<option value="0" <?php echo (isset($produto) && $produto->produto_ativo == 0) ? 'selected' : ''; ?>>Não</option>
									</select>
									<?php echo form_error('produto_ativo', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<hr>

							<!-- Descrições -->
							<h6 class="text-primary mb-3"><i class="fas fa-align-left"></i> Descrições</h6>
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="inputResumo">Resumo do produto *</label>
									<textarea class="form-control" name="produto_resumo" id="inputResumo" rows="3"><?php echo isset($produto) ? $produto->produto_resumo : ''; ?></textarea>
									<?php echo form_error('produto_resumo', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="inputDescricao">Descrição completa</label>
									<textarea class="form-control" name="produto_descricao" id="inputDescricao" rows="5"><?php echo isset($produto) ? $produto->produto_descricao : ''; ?></textarea>
									<?php echo form_error('produto_descricao', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<hr>

							<!-- Imagens do Produto -->
							<h6 class="text-primary mb-3"><i class="fas fa-images"></i> Imagens do Produto</h6>
							
							<?php if (isset($imagens) && !empty($imagens)): ?>
								<div class="row mb-3">
									<?php foreach ($imagens as $imagem): ?>
										<div class="col-md-3 mb-3">
											<div class="card">
												<img src="<?php echo base_url('public/uploads/produtos/' . $imagem->produto_imagem_nome); ?>" class="card-img-top" alt="Imagem do produto" style="height: 200px; object-fit: cover;">
												<div class="card-body p-2">
													<?php if ($imagem->produto_imagem_principal == 1): ?>
														<span class="badge badge-success mb-2">Principal</span>
													<?php else: ?>
														<a href="<?php echo base_url('restrita/produtos/imagem_principal/' . $imagem->produto_imagem_id); ?>" class="badge badge-info mb-2">Definir como principal</a>
													<?php endif; ?>
													<a href="<?php echo base_url('restrita/produtos/excluir_imagem/' . $imagem->produto_imagem_id); ?>" class="badge badge-danger mb-2" onclick="return confirm('Deseja realmente excluir esta imagem?')">Excluir</a>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="inputImagens">Adicionar imagens (múltiplas)</label>
									<input type="file" class="form-control-file" name="produto_imagens[]" id="inputImagens" accept="image/*" multiple>
									<small class="form-text text-muted">Formatos aceitos: JPG, JPEG, PNG, GIF, WEBP. Tamanho máximo: 2MB por imagem. <?php echo !isset($produto) ? 'A primeira imagem será definida como principal.' : ''; ?></small>
								</div>
							</div>

							<?php if (isset($produto)): ?>
								<input type="hidden" name="produto_id" value="<?php echo isset($produto) ? $produto->produto_id : ''; ?>">
							<?php endif; ?>
						</div>

						<div class="card-footer">
							<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
							<a href="<?php echo base_url('restrita/produtos'); ?>" class="btn btn-secondary ml-2"><i class="fas fa-arrow-left"></i> Voltar</a>
						</div>

						<?php echo form_close(); ?>

					</div>
				</div>
			</div>
		</div>
	</section>

	<?php $this->load->view('restrita/layout/setting-sidebar'); ?>
</div>
