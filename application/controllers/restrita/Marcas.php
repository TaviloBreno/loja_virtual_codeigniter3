<?php

defined('BASEPATH') or exit('Ação não permitida');


class Marcas extends CI_Controller
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
			'marcas' => $this->core_model->get_all('marcas'),
			'titulo' => 'Marcas cadastradas',
			'styles' => array(
				'bundles/datatables/datatables.min.css',
				'bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css',
			),
			'scripts' => array(
				'bundles/datatables/datatables.min.js',
				'bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js',
				'bundles/jquery-ui/jquery-ui.min.js',
				'js/page/datatables.js',
			)
		);

		$this->load->view('restrita/layout/header', $data);
		$this->load->view('restrita/marcas/index');
		$this->load->view('restrita/layout/footer');
	}

	public function core($marca_id = NULL)
	{
		if (!$marca_id) {
			// Cadastrar nova marca
			$this->form_validation->set_rules('marca_nome', 'Nome da marca', 'trim|required|min_length[2]|max_length[45]|callback_valida_marca');
			$this->form_validation->set_rules('marca_meta_link', 'Meta link da marca', 'trim|required|min_length[2]|max_length[255]|callback_valida_meta_link');
			$this->form_validation->set_rules('marca_ativa', 'Situação da marca', 'trim|required|in_list[0,1]');

			if ($this->form_validation->run()) {

				$data = elements(
					array(
						'marca_nome',
						'marca_meta_link',
						'marca_ativa',
					),
					$this->input->post()
				);

				$data = html_escape($data);

				if ($this->core_model->insert('marcas', $data)) {
					$this->session->set_flashdata('sucesso', 'Marca cadastrada com sucesso');
				} else {
					$this->session->set_flashdata('error', 'Erro ao cadastrar a marca');
				}

				redirect('restrita/marcas');
			} else {
				$data = array(
					'titulo' => 'Cadastrar marca',
				);

				$this->load->view('restrita/layout/header', $data);
				$this->load->view('restrita/marcas/core');
				$this->load->view('restrita/layout/footer');
			}

		} else {
			// Editar marca existente
			if (!$marca = $this->core_model->get_by_id('marcas', array('marca_id' => $marca_id))) {

				$this->session->set_flashdata('error', 'Marca não encontrada');
				redirect('restrita/marcas');
			} else {

				$this->form_validation->set_rules('marca_nome', 'Nome da marca', 'trim|required|min_length[2]|max_length[45]|callback_valida_marca');
				$this->form_validation->set_rules('marca_meta_link', 'Meta link da marca', 'trim|required|min_length[2]|max_length[255]|callback_valida_meta_link');
				$this->form_validation->set_rules('marca_ativa', 'Situação da marca', 'trim|required|in_list[0,1]');

				if ($this->form_validation->run()) {

					$data = elements(
						array(
							'marca_nome',
							'marca_meta_link',
							'marca_ativa',
						),
						$this->input->post()
					);

					$data = html_escape($data);

					if ($this->core_model->update('marcas', $data, array('marca_id' => $marca_id))) {
						$this->session->set_flashdata('sucesso', 'Marca atualizada com sucesso');
					} else {
						$this->session->set_flashdata('error', 'Erro ao atualizar a marca');
					}

					redirect('restrita/marcas');
				} else {
					$data = array(
						'titulo' => 'Editar marca',
						'marca' => $marca,
					);

					$this->load->view('restrita/layout/header', $data);
					$this->load->view('restrita/marcas/core');
					$this->load->view('restrita/layout/footer');
				}
			}
		}
	}

	public function delete($marca_id = NULL)
	{
		if (!$marca_id || !$marca = $this->core_model->get_by_id('marcas', array('marca_id' => $marca_id))) {

			$this->session->set_flashdata('error', 'Marca não encontrada');
			redirect('restrita/marcas');
		} else {

			if ($this->core_model->delete('marcas', array('marca_id' => $marca_id))) {

				$this->session->set_flashdata('sucesso', 'Marca excluída com sucesso');
			} else {

				$this->session->set_flashdata('error', 'Erro ao excluir a marca');
			}

			redirect('restrita/marcas');
		}
	}

	public function valida_marca($marca_nome)
	{
		$marca_id = $this->input->post('marca_id');

		if (!$marca_id) {
			if ($this->core_model->get_by_id('marcas', array('marca_nome' => $marca_nome))) {
				$this->form_validation->set_message('valida_marca', 'Essa marca já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			if ($this->core_model->get_by_id('marcas', array('marca_nome' => $marca_nome, 'marca_id !=' => $marca_id))) {
				$this->form_validation->set_message('valida_marca', 'Essa marca já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	public function valida_meta_link($marca_meta_link)
	{
		$marca_id = $this->input->post('marca_id');

		if (!$marca_id) {
			if ($this->core_model->get_by_id('marcas', array('marca_meta_link' => $marca_meta_link))) {
				$this->form_validation->set_message('valida_meta_link', 'Esse meta link já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			if ($this->core_model->get_by_id('marcas', array('marca_meta_link' => $marca_meta_link, 'marca_id !=' => $marca_id))) {
				$this->form_validation->set_message('valida_meta_link', 'Esse meta link já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}
}
