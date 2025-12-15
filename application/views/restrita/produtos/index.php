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
							<div class="card-header-action">
								<a href="<?php echo base_url('restrita/produtos/core'); ?>" class="btn btn-primary">
									<i class="fas fa-plus"></i> Cadastrar
								</a>
							</div>
						</div>
						<div class="card-body">

							<?php if ($message = $this->session->flashdata('sucesso')): ?>
								<div class="alert alert-success alert-dismissible show fade">
									<div class="alert-body">
										<button class="close" data-dismiss="alert">
											<span>×</span>
										</button>
										<i class="fas fa-check-circle"></i> <?php echo $message; ?>
									</div>
								</div>
							<?php endif; ?>

							<?php if ($message = $this->session->flashdata('error')): ?>
								<div class="alert alert-danger alert-dismissible show fade">
									<div class="alert-body">
										<button class="close" data-dismiss="alert">
											<span>×</span>
										</button>
										<i class="fas fa-times-circle"></i> <?php echo $message; ?>
									</div>
								</div>
							<?php endif; ?>

							<div class="table-responsive">
								<table class="table table-striped data-table" id="table-1">
									<thead>
										<tr>
											<th class="text-center d-none d-md-table-cell">#</th>
											<th class="d-none d-xl-table-cell">Imagem</th>
											<th class="d-none d-lg-table-cell">Código</th>
											<th>Produto</th>
											<th class="d-none d-lg-table-cell">Categoria</th>
											<th class="d-none d-xl-table-cell">Marca</th>
											<th>Valor</th>
											<th class="d-none d-md-table-cell">Estoque</th>
											<th class="d-none d-md-table-cell">Ativo</th>
											<th class="text-center nosort" style="min-width: 100px;">Ações</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($produtos as $produto): ?>
											<?php 
												// Buscar nome da categoria
												$categoria = $this->core_model->get_by_id('categorias', array('categoria_id' => $produto->produto_categoria_id));
												// Buscar nome da marca
												$marca = $this->core_model->get_by_id('marcas', array('marca_id' => $produto->produto_marca_id));
												// Buscar imagem principal
												$imagem_principal = $this->core_model->get_by_id('produtos_imagens', array('produto_id' => $produto->produto_id, 'produto_imagem_principal' => 1));
											?>
											<tr>
												<td class="text-center d-none d-md-table-cell"><?php echo $produto->produto_id; ?></td>
												<td class="d-none d-xl-table-cell">
													<?php if ($imagem_principal): ?>
														<img src="<?php echo base_url('public/uploads/produtos/' . $imagem_principal->produto_imagem_nome); ?>" alt="<?php echo $produto->produto_nome; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
													<?php else: ?>
														<img src="<?php echo base_url('public/assets/img/no-image.png'); ?>" alt="Sem imagem" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
													<?php endif; ?>
												</td>
												<td class="d-none d-lg-table-cell"><?php echo $produto->produto_codigo; ?></td>
												<td><?php echo $produto->produto_nome; ?></td>
												<td class="d-none d-lg-table-cell"><?php echo $categoria ? $categoria->categoria_nome : 'N/A'; ?></td>
												<td class="d-none d-xl-table-cell"><?php echo $marca ? $marca->marca_nome : 'N/A'; ?></td>
												<td class="text-nowrap">R$ <?php echo number_format($produto->produto_valor, 2, ',', '.'); ?></td>
												<td class="d-none d-md-table-cell">
													<?php if ($produto->produto_controlar_estoque == 1): ?>
														<span class="badge badge-info"><?php echo $produto->produto_quantidade_estoque; ?></span>
													<?php else: ?>
														<span class="badge badge-secondary">N/A</span>
													<?php endif; ?>
												</td>
												<td class="d-none d-md-table-cell">
													<?php if ($produto->produto_ativo == 1): ?>
														<span class="badge badge-success">Sim</span>
													<?php else: ?>
														<span class="badge badge-danger">Não</span>
													<?php endif; ?>
												</td>
												<td class="text-center text-nowrap">
													<a href="<?php echo base_url('restrita/produtos/core/' . $produto->produto_id); ?>" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="Editar">
														<i class="fas fa-pencil-alt"></i>
													</a>
													<button type="button" class="btn btn-danger btn-action btn-delete" data-toggle="tooltip" title="Excluir" data-id="<?php echo $produto->produto_id; ?>" data-nome="<?php echo $produto->produto_nome; ?>">
														<i class="fas fa-trash"></i>
													</button>
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
