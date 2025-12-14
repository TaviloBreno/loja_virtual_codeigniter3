<?php

defined('BASEPATH') or exit('Ação não permitida');


class Categorias extends CI_Controller
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
			'categorias' => $this->core_model->get_all('categorias_pai'),
			'titulo' => 'Categorias cadastradas',
			'styles' => array(
				'bundles/datatables/datatables.min.css',
				'bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css',
			),
			'scripts' => array(
				'bundles/datatables/datatables.min.js',
				'bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js',
				'bundles/jquery-ui/jquery-ui.min.js',
				'js/page/datatables.js',
				'js/categorias.js',
			)
		);

		$this->load->view('restrita/layout/header', $data);
		$this->load->view('restrita/categorias/index');
		$this->load->view('restrita/layout/footer');
	}

	public function core($categoria_pai_id = NULL)
	{
		if (!$categoria_pai_id) {
			// Cadastrar nova categoria
			$this->form_validation->set_rules('categoria_pai_nome', 'Nome da categoria', 'trim|required|min_length[2]|max_length[45]|callback_valida_categoria');
			$this->form_validation->set_rules('categoria_pai_meta_link', 'Meta link da categoria', 'trim|required|min_length[2]|max_length[100]|callback_valida_meta_link');
			$this->form_validation->set_rules('categoria_pai_ativa', 'Situação da categoria', 'trim|required|in_list[0,1]');

			if ($this->form_validation->run()) {

				$data = elements(
					array(
						'categoria_pai_nome',
						'categoria_pai_meta_link',
						'categoria_pai_ativa',
					),
					$this->input->post()
				);

				$data = html_escape($data);

				if ($this->core_model->insert('categorias_pai', $data)) {
					$this->session->set_flashdata('sucesso', 'Categoria cadastrada com sucesso');
				} else {
					$this->session->set_flashdata('error', 'Erro ao cadastrar a categoria');
				}

				redirect('restrita/categorias');
			} else {
				$data = array(
					'titulo' => 'Cadastrar categoria',
				);

				$this->load->view('restrita/layout/header', $data);
				$this->load->view('restrita/categorias/core');
				$this->load->view('restrita/layout/footer');
			}

		} else {
			// Editar categoria existente
			if (!$categoria = $this->core_model->get_by_id('categorias_pai', array('categoria_pai_id' => $categoria_pai_id))) {

				$this->session->set_flashdata('error', 'Categoria não encontrada');
				redirect('restrita/categorias');
			} else {

				$this->form_validation->set_rules('categoria_pai_nome', 'Nome da categoria', 'trim|required|min_length[2]|max_length[45]|callback_valida_categoria');
				$this->form_validation->set_rules('categoria_pai_meta_link', 'Meta link da categoria', 'trim|required|min_length[2]|max_length[100]|callback_valida_meta_link');
				$this->form_validation->set_rules('categoria_pai_ativa', 'Situação da categoria', 'trim|required|in_list[0,1]');

				if ($this->form_validation->run()) {

					$data = elements(
						array(
							'categoria_pai_nome',
							'categoria_pai_meta_link',
							'categoria_pai_ativa',
						),
						$this->input->post()
					);

					$data = html_escape($data);

					if ($this->core_model->update('categorias_pai', $data, array('categoria_pai_id' => $categoria_pai_id))) {
						$this->session->set_flashdata('sucesso', 'Categoria atualizada com sucesso');
					} else {
						$this->session->set_flashdata('error', 'Erro ao atualizar a categoria');
					}

					redirect('restrita/categorias');
				} else {
					$data = array(
						'titulo' => 'Editar categoria',
						'categoria' => $categoria,
					);

					$this->load->view('restrita/layout/header', $data);
					$this->load->view('restrita/categorias/core');
					$this->load->view('restrita/layout/footer');
				}
			}
		}
	}

	public function excluir($categoria_pai_id = NULL)
	{
		if (!$categoria_pai_id || !$categoria = $this->core_model->get_by_id('categorias_pai', array('categoria_pai_id' => $categoria_pai_id))) {

			$this->session->set_flashdata('error', 'Categoria não encontrada');
			redirect('restrita/categorias');
		} else {

			if ($this->core_model->delete('categorias_pai', array('categoria_pai_id' => $categoria_pai_id))) {

				$this->session->set_flashdata('sucesso', 'Categoria excluída com sucesso');
			} else {

				$this->session->set_flashdata('error', 'Erro ao excluir a categoria');
			}

			redirect('restrita/categorias');
		}
	}

	public function valida_categoria($categoria_pai_nome)
	{
		$categoria_pai_id = $this->input->post('categoria_pai_id');

		if (!$categoria_pai_id) {
			if ($this->core_model->get_by_id('categorias_pai', array('categoria_pai_nome' => $categoria_pai_nome))) {
				$this->form_validation->set_message('valida_categoria', 'Essa categoria já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			if ($this->core_model->get_by_id('categorias_pai', array('categoria_pai_nome' => $categoria_pai_nome, 'categoria_pai_id !=' => $categoria_pai_id))) {
				$this->form_validation->set_message('valida_categoria', 'Essa categoria já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	public function valida_meta_link($categoria_pai_meta_link)
	{
		$categoria_pai_id = $this->input->post('categoria_pai_id');

		if (!$categoria_pai_id) {
			if ($this->core_model->get_by_id('categorias_pai', array('categoria_pai_meta_link' => $categoria_pai_meta_link))) {
				$this->form_validation->set_message('valida_meta_link', 'Esse meta link já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			if ($this->core_model->get_by_id('categorias_pai', array('categoria_pai_meta_link' => $categoria_pai_meta_link, 'categoria_pai_id !=' => $categoria_pai_id))) {
				$this->form_validation->set_message('valida_meta_link', 'Esse meta link já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}
}
