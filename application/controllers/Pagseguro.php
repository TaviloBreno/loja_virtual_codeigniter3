<?php

defined('BASEPATH') or exit('Ação não permitida');

/**
 * Controller público para notificações e retorno do PagSeguro
 */
class Pagseguro extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('pagseguro');
	}

	/**
	 * Receber notificações do PagSeguro
	 */
	public function notificacao()
	{
		// Receber código da notificação
		$notification_code = $this->input->post('notificationCode');

		if (!$notification_code) {
			log_message('error', 'PagSeguro: Código de notificação não recebido');
			return;
		}

		// Processar notificação
		$transacao = $this->pagseguro->processar_notificacao($notification_code);

		if ($transacao) {
			// Salvar/atualizar transação no banco
			$dados = array(
				'transacao_codigo' => $transacao['code'],
				'transacao_referencia' => $transacao['reference'],
				'transacao_status' => $transacao['status'],
				'transacao_metodo_pagamento' => $transacao['payment_method'],
				'transacao_valor_bruto' => $transacao['gross_amount'],
				'transacao_valor_liquido' => $transacao['net_amount'],
				'transacao_data_atualizacao' => date('Y-m-d H:i:s'),
			);

			// Verificar se já existe
			$existe = $this->core_model->get_by_id('transacoes', array('transacao_codigo' => $transacao['code']));

			if ($existe) {
				// Atualizar
				$this->core_model->update('transacoes', $dados, array('transacao_codigo' => $transacao['code']));
				log_message('info', 'PagSeguro: Transação atualizada - ' . $transacao['code']);
			} else {
				// Inserir nova
				$dados['transacao_ativa'] = 1;
				$dados['transacao_data_cadastro'] = date('Y-m-d H:i:s');
				$this->core_model->insert('transacoes', $dados);
				log_message('info', 'PagSeguro: Nova transação registrada - ' . $transacao['code']);
			}

			// Processar pedido de acordo com o status
			$this->processar_pedido($transacao);
		} else {
			log_message('error', 'PagSeguro: Erro ao processar notificação - ' . $notification_code);
		}
	}

	/**
	 * Página de retorno após pagamento
	 */
	public function retorno()
	{
		$transaction_id = $this->input->get('transaction_id');

		$data = array(
			'titulo' => 'Pagamento Processado',
			'transaction_id' => $transaction_id,
		);

		$this->load->view('pagseguro/retorno', $data);
	}

	/**
	 * Processar pedido de acordo com status
	 * @param array $transacao
	 */
	private function processar_pedido($transacao)
	{
		$status = (int) $transacao['status'];

		switch ($status) {
			case 1: // Aguardando pagamento
				log_message('info', 'PagSeguro: Aguardando pagamento - ' . $transacao['code']);
				break;

			case 2: // Em análise
				log_message('info', 'PagSeguro: Pagamento em análise - ' . $transacao['code']);
				break;

			case 3: // Paga
				log_message('info', 'PagSeguro: Pagamento confirmado - ' . $transacao['code']);
				// Aqui você pode liberar o pedido, enviar e-mail, etc.
				break;

			case 4: // Disponível
				log_message('info', 'PagSeguro: Pagamento disponível - ' . $transacao['code']);
				break;

			case 5: // Em disputa
				log_message('warning', 'PagSeguro: Pagamento em disputa - ' . $transacao['code']);
				break;

			case 6: // Devolvida
				log_message('warning', 'PagSeguro: Pagamento devolvido - ' . $transacao['code']);
				// Aqui você pode cancelar o pedido, estornar estoque, etc.
				break;

			case 7: // Cancelada
				log_message('warning', 'PagSeguro: Pagamento cancelado - ' . $transacao['code']);
				// Aqui você pode cancelar o pedido
				break;
		}
	}
}
