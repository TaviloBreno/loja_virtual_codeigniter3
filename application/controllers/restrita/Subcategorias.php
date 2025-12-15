<?php

defined('BASEPATH') or exit('Ação não permitida');


class Subcategorias extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		// Verificar se está logado
		if (!$this->ion_auth->logged_in()) {
			redirect('login');
		}

		$this->load->model('core_model');
	}

	public function index()
	{
		$data = array(
			'subcategorias' => $this->core_model->get_all('categorias'),
			'titulo' => 'Subcategorias cadastradas',
			'styles' => array(
				'bundles/datatables/datatables.min.css',
				'bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css',
			),
			'scripts' => array(
				'bundles/datatables/datatables.min.js',
				'bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js',
				'bundles/jquery-ui/jquery-ui.min.js',
				'js/page/datatables.js',
				'js/subcategorias.js',
			)
		);

		$this->load->view('restrita/layout/header', $data);
		$this->load->view('restrita/subcategorias/index');
		$this->load->view('restrita/layout/footer');
	}

	public function core($categoria_id = NULL)
	{
		// Buscar todas as categorias pai para o select
		$categorias_pai = $this->core_model->get_all('categorias_pai', array('categoria_pai_ativa' => 1));
		
		if (!$categoria_id) {
			// Cadastrar nova subcategoria
			$this->form_validation->set_rules('categoria_nome', 'Nome da subcategoria', 'trim|required|min_length[2]|max_length[45]|callback_valida_subcategoria');
			$this->form_validation->set_rules('categoria_meta_link', 'Meta link da subcategoria', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('categoria_pai_id', 'Categoria', 'trim|required|integer');
			$this->form_validation->set_rules('categoria_ativa', 'Situação da subcategoria', 'trim|required|in_list[0,1]');

			if ($this->form_validation->run()) {
				$data = elements(
					array(
						'categoria_nome',
						'categoria_meta_link',
						'categoria_pai_id',
						'categoria_ativa'
					),
					$this->input->post()
				);

				$data = html_escape($data);

				if ($this->core_model->insert('categorias', $data)) {
					$this->session->set_flashdata('sucesso', 'Subcategoria cadastrada com sucesso');
				} else {
					$this->session->set_flashdata('error', 'Erro ao cadastrar a subcategoria');
				}

				redirect('restrita/subcategorias');
			} else {
				$data = array(
					'titulo' => 'Cadastrar subcategoria',
					'categorias_pai' => $categorias_pai,
					'scripts' => array(
						'js/subcategorias_form.js',
					),
				);

				$this->load->view('restrita/layout/header', $data);
				$this->load->view('restrita/subcategorias/core');
				$this->load->view('restrita/layout/footer');
			}
		} else {
			// Editar subcategoria existente
			$subcategoria = $this->core_model->get_by_id('categorias', array('categoria_id' => $categoria_id));

			if (!$subcategoria) {
				$this->session->set_flashdata('error', 'Subcategoria não encontrada');
				redirect('restrita/subcategorias');
			}

			$this->form_validation->set_rules('categoria_nome', 'Nome da subcategoria', 'trim|required|min_length[2]|max_length[45]|callback_valida_subcategoria');
			$this->form_validation->set_rules('categoria_meta_link', 'Meta link da subcategoria', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('categoria_pai_id', 'Categoria', 'trim|required|integer');
			$this->form_validation->set_rules('categoria_ativa', 'Situação da subcategoria', 'trim|required|in_list[0,1]');

			if ($this->form_validation->run()) {
				$data = elements(
					array(
						'categoria_nome',
						'categoria_meta_link',
						'categoria_pai_id',
						'categoria_ativa'
					),
					$this->input->post()
				);

				$data = html_escape($data);

				if ($this->core_model->update('categorias', $data, array('categoria_id' => $categoria_id))) {
					$this->session->set_flashdata('sucesso', 'Subcategoria atualizada com sucesso');
				} else {
					$this->session->set_flashdata('error', 'Erro ao atualizar a subcategoria');
				}

				redirect('restrita/subcategorias');
			} else {
				$data = array(
					'titulo' => 'Editar subcategoria',
					'subcategoria' => $subcategoria,
					'categorias_pai' => $categorias_pai,
					'scripts' => array(
						'js/subcategorias_form.js',
					),
				);

				$this->load->view('restrita/layout/header', $data);
				$this->load->view('restrita/subcategorias/core');
				$this->load->view('restrita/layout/footer');
			}
		}
	}

	public function excluir($categoria_id = NULL)
	{
		if (!$categoria_id || !$this->core_model->get_by_id('categorias', array('categoria_id' => $categoria_id))) {
			$this->session->set_flashdata('error', 'Subcategoria não encontrada');
			redirect('restrita/subcategorias');
		}

		if ($this->core_model->delete('categorias', array('categoria_id' => $categoria_id))) {
			$this->session->set_flashdata('sucesso', 'Subcategoria excluída com sucesso');
		} else {
			$this->session->set_flashdata('error', 'Erro ao excluir a subcategoria');
		}

		redirect('restrita/subcategorias');
	}

	// Validação de nome único para subcategoria
	public function valida_subcategoria($categoria_nome)
	{
		$categoria_id = $this->input->post('categoria_id');

		if ($categoria_id) {
			// Editando - verificar se o nome já existe em outro registro
			$this->db->where('categoria_nome', $categoria_nome);
			$this->db->where('categoria_id !=', $categoria_id);
			$subcategoria = $this->db->get('categorias')->row();
		} else {
			// Cadastrando - verificar se o nome já existe
			$subcategoria = $this->core_model->get_by_id('categorias', array('categoria_nome' => $categoria_nome));
		}

		if ($subcategoria) {
			$this->form_validation->set_message('valida_subcategoria', 'Essa subcategoria já existe');
			return FALSE;
		} else {
			return TRUE;
		}
	}
}
