<?php

defined('BASEPATH') or exit('Ação não permitida');


class Pagamentos extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		// Verificar se está logado
		if (!$this->ion_auth->logged_in()) {
			redirect('login');
		}

		$this->load->library('pagseguro');
	}

	/**
	 * Listagem de transações
	 */
	public function index()
	{
		$data = array(
			'titulo' => 'Transações de Pagamento',
			'transacoes' => $this->core_model->get_all('transacoes', array('transacao_ativa' => 1)),
		);

		$this->load->view('restrita/layout/header', $data);
		$this->load->view('restrita/pagamentos/index');
		$this->load->view('restrita/layout/footer');
	}

	/**
	 * Teste de checkout
	 */
	public function testar_checkout()
	{
		$data = array(
			'titulo' => 'Testar Checkout PagSeguro',
			'scripts' => array(
				'js/pagseguro.js',
			),
		);

		$this->load->view('restrita/layout/header', $data);
		$this->load->view('restrita/pagamentos/testar_checkout');
		$this->load->view('restrita/layout/footer');
	}

	/**
	 * Criar sessão PagSeguro via AJAX
	 */
	public function criar_sessao()
	{
		$this->output->set_content_type('application/json');

		$session_id = $this->pagseguro->criar_sessao();

		if ($session_id) {
			echo json_encode(array(
				'success' => true,
				'session_id' => $session_id
			));
		} else {
			echo json_encode(array(
				'success' => false,
				'message' => 'Erro ao criar sessão PagSeguro'
			));
		}
	}

	/**
	 * Processar checkout via AJAX
	 */
	public function processar_checkout()
	{
		$this->output->set_content_type('application/json');

		// Validar dados
		$this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]');
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
		$this->form_validation->set_rules('ddd', 'DDD', 'required|exact_length[2]');
		$this->form_validation->set_rules('telefone', 'Telefone', 'required');
		$this->form_validation->set_rules('cpf', 'CPF', 'required');

		if (!$this->form_validation->run()) {
			echo json_encode(array(
				'success' => false,
				'message' => validation_errors()
			));
			return;
		}

		// Dados do cliente
		$dados = array(
			'nome' => $this->input->post('nome'),
			'email' => $this->input->post('email'),
			'ddd' => $this->input->post('ddd'),
			'telefone' => $this->input->post('telefone'),
			'cpf' => $this->input->post('cpf'),
			'itens' => array(
				array(
					'id' => 1,
					'descricao' => 'Produto Teste',
					'quantidade' => 1,
					'valor' => 100.00
				)
			)
		);

		// Criar checkout
		$url = $this->pagseguro->criar_checkout($dados);

		if ($url) {
			echo json_encode(array(
				'success' => true,
				'url' => $url
			));
		} else {
			echo json_encode(array(
				'success' => false,
				'message' => 'Erro ao criar checkout'
			));
		}
	}

	/**
	 * Consultar transação via AJAX
	 */
	public function consultar_transacao()
	{
		$this->output->set_content_type('application/json');

		$transaction_code = $this->input->post('transaction_code');

		if (!$transaction_code) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Código de transação não informado'
			));
			return;
		}

		$resultado = $this->pagseguro->consultar_transacao($transaction_code);

		if ($resultado) {
			$resultado['status_descricao'] = $this->pagseguro->get_status_descricao($resultado['status']);
			
			echo json_encode(array(
				'success' => true,
				'data' => $resultado
			));
		} else {
			echo json_encode(array(
				'success' => false,
				'message' => 'Transação não encontrada'
			));
		}
	}

	/**
	 * Configurações do PagSeguro
	 */
	public function configuracoes()
	{
		// Buscar configurações atuais
		$sistema = $this->core_model->get_by_id('sistema', array('sistema_id' => 1));

		// Se for POST, salvar
		if ($this->input->method() == 'post') {
			
			$this->form_validation->set_rules('sistema_pagseguro_email', 'E-mail PagSeguro', 'required|valid_email');
			$this->form_validation->set_rules('sistema_pagseguro_token', 'Token PagSeguro', 'required|min_length[32]');

			if ($this->form_validation->run()) {
				$data = array(
					'sistema_pagseguro_email' => $this->input->post('sistema_pagseguro_email'),
					'sistema_pagseguro_token' => $this->input->post('sistema_pagseguro_token'),
					'sistema_pagseguro_sandbox' => $this->input->post('sistema_pagseguro_sandbox') ? 1 : 0,
				);

				if ($this->core_model->update('sistema', $data, array('sistema_id' => 1))) {
					$this->session->set_flashdata('sucesso', 'Configurações do PagSeguro atualizadas com sucesso!');
				} else {
					$this->session->set_flashdata('erro', 'Erro ao atualizar configurações.');
				}

				redirect('restrita/pagamentos/configuracoes');
			} else {
				$this->session->set_flashdata('erro', 'Verifique os campos obrigatórios.');
			}
		}

		$data = array(
			'titulo' => 'Configurações PagSeguro',
			'sistema' => $sistema,
		);

		$this->load->view('restrita/layout/header', $data);
		$this->load->view('restrita/pagamentos/configuracoes');
		$this->load->view('restrita/layout/footer');
	}
}
