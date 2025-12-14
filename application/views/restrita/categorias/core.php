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
							<h4><?php echo isset($titulo) ? $titulo : 'Gerenciar Categorias'; ?></h4>
						</div>

						<?php $atributos = array(
							'name' => 'form_core',
						);

						if (isset($categoria)) {
							$categoria_pai_id = $categoria->categoria_pai_id;
						} else {
							$categoria_pai_id = '';
						}
						?>

						<?php echo form_open('restrita/categorias/core/' . $categoria_pai_id, $atributos); ?>

						<div class="card-body">
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputCategoriaNome">Nome da categoria</label>
									<input type="text" class="form-control" name="categoria_pai_nome" id="inputCategoriaNome" value="<?php echo isset($categoria) ? $categoria->categoria_pai_nome : ''; ?>">
									<?php echo form_error('categoria_pai_nome', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-6">
									<label for="inputCategoriaMetaLink">Meta link</label>
									<input type="text" class="form-control" name="categoria_pai_meta_link" id="inputCategoriaMetaLink" value="<?php echo isset($categoria) ? $categoria->categoria_pai_meta_link : ''; ?>">
									<?php echo form_error('categoria_pai_meta_link', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputCategoriaAtiva">Categoria ativa</label>
									<select class="form-control" name="categoria_pai_ativa" id="inputCategoriaAtiva">
										<option value="1" <?php echo (isset($categoria) && $categoria->categoria_pai_ativa == 1) ? 'selected' : ''; ?>>Sim</option>
										<option value="0" <?php echo (isset($categoria) && $categoria->categoria_pai_ativa == 0) ? 'selected' : ''; ?>>Não</option>
									</select>
									<?php echo form_error('categoria_pai_ativa', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<?php if (isset($categoria)): ?>
								<input type="hidden" name="categoria_pai_id" value="<?php echo isset($categoria) ? $categoria->categoria_pai_id : ''; ?>">
							<?php endif; ?>
						</div>

						<div class="card-footer">
							<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
							<a href="<?php echo base_url('restrita/categorias'); ?>" class="btn btn-secondary ml-2"><i class="fas fa-arrow-left"></i> Voltar</a>
						</div>

						<?php echo form_close(); ?>

					</div>
				</div>
			</div>
		</div>
	</section>

	<?php $this->load->view('restrita/layout/setting-sidebar'); ?>
</div>
