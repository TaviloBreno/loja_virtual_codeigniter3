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
							<h4><?php echo isset($titulo) ? $titulo : 'Gerenciar Marcas'; ?></h4>
						</div>

						<?php $atributos = array(
							'name' => 'form_core',
						);

						if (isset($marca)) {
							$marca_id = $marca->marca_id;
						} else {
							$marca_id = '';
						}
						?>

						<?php echo form_open('restrita/marcas/core/' . $marca_id, $atributos); ?>

						<div class="card-body">
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputMarcaNome">Nome da marca</label>
									<input type="text" class="form-control" name="marca_nome" id="inputMarcaNome" value="<?php echo isset($marca) ? $marca->marca_nome : ''; ?>">
									<?php echo form_error('marca_nome', '<div class="text-danger">', '</div>'); ?>
								</div>
								<div class="form-group col-md-6">
									<label for="inputMarcaMetaLink">Meta link</label>
									<input type="text" class="form-control" name="marca_meta_link" id="inputMarcaMetaLink" value="<?php echo isset($marca) ? $marca->marca_meta_link : ''; ?>">
									<?php echo form_error('marca_meta_link', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputMarcaAtiva">Marca ativa</label>
									<select class="form-control" name="marca_ativa" id="inputMarcaAtiva">
										<option value="1" <?php echo (isset($marca) && $marca->marca_ativa == 1) ? 'selected' : ''; ?>>Sim</option>
										<option value="0" <?php echo (isset($marca) && $marca->marca_ativa == 0) ? 'selected' : ''; ?>>Não</option>
									</select>
									<?php echo form_error('marca_ativa', '<div class="text-danger">', '</div>'); ?>
								</div>
							</div>

							<?php if (isset($marca)): ?>
								<input type="hidden" name="marca_id" value="<?php echo isset($marca) ? $marca->marca_id : ''; ?>">
							<?php endif; ?>
						</div>

						<div class="card-footer">
							<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
							<a href="<?php echo base_url('restrita/marcas'); ?>" class="btn btn-secondary ml-2"><i class="fas fa-arrow-left"></i> Voltar</a>
						</div>

						<?php echo form_close(); ?>

					</div>
				</div>
			</div>
		</div>
	</section>

	<?php $this->load->view('restrita/layout/setting-sidebar'); ?>
</div>
