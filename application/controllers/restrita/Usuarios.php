<?php

defined('BASEPATH') or exit('Ação não permitida');


class Usuarios extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = array(
			'usuarios' => $this->ion_auth->users()->result(),
			'titulo' => 'Usuários do sistema',
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
		$this->load->view('restrita/usuarios/index');
		$this->load->view('restrita/layout/footer');
	}

	public function core($usuario_id = NULL)
	{
		if (!$usuario_id) {
		} else {
			if (!$usuario = $this->ion_auth->user($usuario_id)->row()) {

				$this->session->set_flashdata('error', 'Usuário não encontrado');
				redirect('restrita/usuarios');
			} else {

				$this->form_validation->set_rules('first_name', 'Nome', 'trim|required|min_length[4]|max_length[45]');
				$this->form_validation->set_rules('last_name', 'Sobrenome', 'trim|required|min_length[4]|max_length[45]');
				$this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email|min_length[4]|max_length[100]');
				$this->form_validation->set_rules('username', 'Usuário', 'trim|required|min_length[4]|max_length[45]');
				$this->form_validation->set_rules('active', 'Ativo', 'trim|required|in_list[0,1]');

				if ($this->form_validation->run()) {
					$data = elements(
						array(
							'first_name',
							'last_name',
							'email',
							'username',
							'active',
						),
						$this->input->post()
					);

					$data = html_escape($data);

					$this->ion_auth->update($usuario_id, $data);

					redirect('restrita/usuarios');
				} else {

					$data = array(
						'titulo' => 'Editar usuário',
						'usuario' => $this->ion_auth->user($usuario_id)->row(),
						'perfil' => $this->ion_auth->get_users_groups($usuario_id)->row(),
						'grupos' => $this->ion_auth->groups()->result(),
					);

					$this->load->view('restrita/layout/header', $data);
					$this->load->view('restrita/usuarios/core');
					$this->load->view('restrita/layout/footer');
				}
			}
		}
	}
}
