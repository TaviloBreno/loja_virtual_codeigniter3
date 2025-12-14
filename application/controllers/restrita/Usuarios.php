<?php

defined('BASEPATH') or exit('Ação não permitida');


class Usuarios extends CI_Controller
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
				'js/usuarios.js',
			)
		);

		$this->load->view('restrita/layout/header', $data);
		$this->load->view('restrita/usuarios/index');
		$this->load->view('restrita/layout/footer');
	}

	public function core($usuario_id = NULL)
	{
		if (!$usuario_id) {
			// Cadastrar novo usuário
			$this->form_validation->set_rules('first_name', 'Nome', 'trim|required|min_length[4]|max_length[45]');
			$this->form_validation->set_rules('last_name', 'Sobrenome', 'trim|required|min_length[4]|max_length[45]');
			$this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email|max_length[100]|callback_valida_email');
			$this->form_validation->set_rules('username', 'Usuário', 'trim|required|min_length[4]|max_length[45]|callback_valida_usuario');
			$this->form_validation->set_rules('password', 'Senha', 'trim|required|min_length[6]|max_length[200]');
			$this->form_validation->set_rules('confirm_password', 'Confirmação de senha', 'trim|required|matches[password]');
			$this->form_validation->set_rules('active', 'Ativo', 'trim|required|in_list[0,1]');
			$this->form_validation->set_rules('perfil', 'Perfil', 'trim|required');

			if ($this->form_validation->run()) {

				$username = $this->input->post('username');
				$password = $this->input->post('password');
				$email = $this->input->post('email');
				$additional_data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'active' => $this->input->post('active'),
				);
				$group = array($this->input->post('perfil'));

				if ($this->ion_auth->register($username, $password, $email, $additional_data, $group)) {
					$this->session->set_flashdata('sucesso', 'Usuário cadastrado com sucesso');
					redirect('restrita/usuarios');
				} else {
					$this->session->set_flashdata('error', 'Erro ao cadastrar o usuário');
					redirect('restrita/usuarios');
				}
			} else {
				$data = array(
					'titulo' => 'Cadastrar usuário',
					'grupos' => $this->ion_auth->groups()->result(),
				);

				$this->load->view('restrita/layout/header', $data);
				$this->load->view('restrita/usuarios/core');
				$this->load->view('restrita/layout/footer');
			}

		} else {
			// Editar usuário existente
			if (!$usuario = $this->ion_auth->user($usuario_id)->row()) {

				$this->session->set_flashdata('error', 'Usuário não encontrado');
				redirect('restrita/usuarios');
			} else {

				$this->form_validation->set_rules('first_name', 'Nome', 'trim|required|min_length[4]|max_length[45]');
				$this->form_validation->set_rules('last_name', 'Sobrenome', 'trim|required|min_length[4]|max_length[45]');
				$this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email|max_length[100]|callback_valida_email');
				$this->form_validation->set_rules('username', 'Usuário', 'trim|required|min_length[4]|max_length[45]|callback_valida_usuario');
				$this->form_validation->set_rules('password', 'Senha', 'trim|min_length[6]|max_length[200]');
				$this->form_validation->set_rules('confirm_password', 'Confirmação de senha', 'trim|matches[password]');
				$this->form_validation->set_rules('active', 'Ativo', 'trim|required|in_list[0,1]');
				$this->form_validation->set_rules('perfil', 'Perfil', 'trim|required');

				if ($this->form_validation->run()) {

					$data = elements(
						array(
							'first_name',
							'last_name',
							'email',
							'username',
							'password',
							'active',
						),
						$this->input->post()
					);

					$password = $this->input->post('password');

					if (!$password) {
						unset($data['password']);
					}

					$data = html_escape($data);

					// Atualizar dados do usuário
					if ($this->ion_auth->update($usuario_id, $data)) {
						
						$perfil_post = $this->input->post('perfil');
						
						if($perfil_post){
							$this->ion_auth->remove_from_group(NULL, $usuario_id);
							$this->ion_auth->add_to_group($perfil_post, $usuario_id);
						}

						$this->session->set_flashdata('sucesso', 'Dados editados com sucesso');
					} else {
						$this->session->set_flashdata('error', 'Erro ao editar os dados');
					}

					redirect('restrita/usuarios');
				} else {
					$data = array(
						'titulo' => 'Editar usuário',
						'usuario' => $usuario,
						'grupos' => $this->ion_auth->groups()->result(),
						'perfil' => $this->ion_auth->get_users_groups($usuario_id)->row(),
					);

					$this->load->view('restrita/layout/header', $data);
					$this->load->view('restrita/usuarios/core');
					$this->load->view('restrita/layout/footer');
				}
			}
		}
	}

	public function delete($usuario_id = NULL)
	{
		if (!$usuario_id || !$usuario = $this->ion_auth->user($usuario_id)->row()) {

			$this->session->set_flashdata('error', 'Usuário não encontrado');
			redirect('restrita/usuarios');
		} else {

			if ($this->ion_auth->is_admin($usuario_id)) {

				$this->session->set_flashdata('error', 'Não é permitido excluir um usuário administrador');
				redirect('restrita/usuarios');
			} else {

				if ($this->ion_auth->delete_user($usuario_id)) {

					$this->session->set_flashdata('sucesso', 'Usuário excluído com sucesso');
				} else {

					$this->session->set_flashdata('error', 'Erro ao excluir o usuário');
				}

				redirect('restrita/usuarios');
			}
		}
	}

	public function valida_email($email)
	{
		$usuario_id = $this->input->post('usuario_id');

		if (!$usuario_id) {
			if ($this->core_model->get_by_id('users', array('email' => $email))) {
				$this->form_validation->set_message('valida_email', 'Esse e-mail já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			if ($this->core_model->get_by_id('users', array('email' => $email, 'id !=' => $usuario_id))) {
				$this->form_validation->set_message('valida_email', 'Esse e-mail já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	public function valida_usuario($username)
	{
		$usuario_id = $this->input->post('usuario_id');

		if (!$usuario_id) {
			if ($this->core_model->get_by_id('users', array('username' => $username))) {
				$this->form_validation->set_message('valida_usuario', 'Esse usuário já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			if ($this->core_model->get_by_id('users', array('username' => $username, 'id !=' => $usuario_id))) {
				$this->form_validation->set_message('valida_usuario', 'Esse usuário já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}
}
