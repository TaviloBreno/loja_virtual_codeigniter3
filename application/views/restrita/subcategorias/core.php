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
							<h4><?php echo isset($titulo) ? $titulo : 'Gerenciar Subcategorias'; ?></h4>
						</div>

						<?php $atributos = array(
							'name' => 'form_core',
						);

						if (isset($subcategoria)) {
							$categoria_id = $subcategoria->categoria_id;
						} else {
							$categoria_id = '';
						}
						?>

						<?php echo form_open('restrita/subcategorias/core/' . $categoria_id, $atributos); ?>

						<div class="card-body">
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputSubcategoriaNome">Nome da subcategoria</label>
									<input type="text" class="form-control" name="categoria_nome" id="inputSubcategoriaNome" value="<?php echo isset($subcategoria) ? $subcategoria->categoria_nome : ''; ?>">
									<?php echo form_error('categoria_nome', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-6">
									<label for="inputSubcategoriaMetaLink">Meta link</label>
									<input type="text" class="form-control" name="categoria_meta_link" id="inputSubcategoriaMetaLink" value="<?php echo isset($subcategoria) ? $subcategoria->categoria_meta_link : ''; ?>" readonly>
									<?php echo form_error('categoria_meta_link', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputCategoriaPai">Categoria</label>
									<select class="form-control" name="categoria_pai_id" id="inputCategoriaPai">
										<option value="">Selecione a categoria</option>
										<?php foreach ($categorias_pai as $categoria_pai): ?>
											<option value="<?php echo $categoria_pai->categoria_pai_id; ?>" 
												<?php echo (isset($subcategoria) && $subcategoria->categoria_pai_id == $categoria_pai->categoria_pai_id) ? 'selected' : ''; ?>>
												<?php echo $categoria_pai->categoria_pai_nome; ?>
											</option>
										<?php endforeach; ?>
									</select>
									<?php echo form_error('categoria_pai_id', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-6">
									<label for="inputSubcategoriaAtiva">Subcategoria ativa</label>
									<select class="form-control" name="categoria_ativa" id="inputSubcategoriaAtiva">
										<option value="1" <?php echo (isset($subcategoria) && $subcategoria->categoria_ativa == 1) ? 'selected' : ''; ?>>Sim</option>
										<option value="0" <?php echo (isset($subcategoria) && $subcategoria->categoria_ativa == 0) ? 'selected' : ''; ?>>Não</option>
									</select>
									<?php echo form_error('categoria_ativa', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<?php if (isset($subcategoria)): ?>
								<input type="hidden" name="categoria_id" value="<?php echo isset($subcategoria) ? $subcategoria->categoria_id : ''; ?>">
							<?php endif; ?>
						</div>

						<div class="card-footer">
							<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
							<a href="<?php echo base_url('restrita/subcategorias'); ?>" class="btn btn-secondary ml-2"><i class="fas fa-arrow-left"></i> Voltar</a>
						</div>

						<?php echo form_close(); ?>

					</div>
				</div>
			</div>
		</div>
	</section>

	<?php $this->load->view('restrita/layout/setting-sidebar'); ?>
</div>
