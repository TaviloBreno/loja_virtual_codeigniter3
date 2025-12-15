<?php
defined('BASEPATH') OR exit('Ação não permitida');

class Pagseguro {

    protected $CI;
    private $email;
    private $token;
    private $sandbox;
    private $url_api;
    private $url_checkout;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('core_model');
        
        // Carregar configurações do banco de dados (tabela sistema)
        $sistema = $this->CI->core_model->get_by_id('sistema', array('sistema_id' => 1));
        
        if ($sistema) {
            $this->email = $sistema->sistema_pagseguro_email;
            $this->token = $sistema->sistema_pagseguro_token;
            $this->sandbox = ($sistema->sistema_pagseguro_sandbox == 1);
        }
        
        // URLs da API
        if ($this->sandbox) {
            $this->url_api = 'https://ws.sandbox.pagseguro.uol.com.br';
            $this->url_checkout = 'https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=';
        } else {
            $this->url_api = 'https://ws.pagseguro.uol.com.br';
            $this->url_checkout = 'https://pagseguro.uol.com.br/v2/checkout/payment.html?code=';
        }
    }

    /**
     * Criar sessão de checkout
     * @return string|false código da sessão ou false em caso de erro
     */
    public function criar_sessao()
    {
        if (!$this->email || !$this->token) {
            return false;
        }

        $url = $this->url_api . '/v2/sessions';
        $params = http_build_query(array(
            'email' => $this->email,
            'token' => $this->token
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $xml = simplexml_load_string($response);
            if ($xml && isset($xml->id)) {
                return (string) $xml->id;
            }
        }

        return false;
    }

    /**
     * Criar checkout transparente
     * @param array $dados
     * @return array|false
     */
    public function checkout_transparente($dados)
    {
        if (!$this->email || !$this->token) {
            return false;
        }

        $url = $this->url_api . '/v2/transactions';

        // Montar XML da requisição
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml .= '<payment>';
        $xml .= '<mode>default</mode>';
        $xml .= '<method>' . $dados['metodo'] . '</method>'; // creditCard, boleto, eft (débito)
        $xml .= '<sender>';
        $xml .= '<name>' . $dados['nome'] . '</name>';
        $xml .= '<email>' . $dados['email'] . '</email>';
        $xml .= '<phone>';
        $xml .= '<areaCode>' . $dados['ddd'] . '</areaCode>';
        $xml .= '<number>' . $dados['telefone'] . '</number>';
        $xml .= '</phone>';
        $xml .= '<documents>';
        $xml .= '<document>';
        $xml .= '<type>CPF</type>';
        $xml .= '<value>' . $dados['cpf'] . '</value>';
        $xml .= '</document>';
        $xml .= '</documents>';
        $xml .= '</sender>';
        $xml .= '<currency>BRL</currency>';
        
        // Itens
        $xml .= '<items>';
        foreach ($dados['itens'] as $index => $item) {
            $xml .= '<item>';
            $xml .= '<id>' . $item['id'] . '</id>';
            $xml .= '<description>' . $item['descricao'] . '</description>';
            $xml .= '<quantity>' . $item['quantidade'] . '</quantity>';
            $xml .= '<amount>' . number_format($item['valor'], 2, '.', '') . '</amount>';
            $xml .= '</item>';
        }
        $xml .= '</items>';
        
        // Shipping (frete)
        if (isset($dados['frete'])) {
            $xml .= '<shipping>';
            $xml .= '<type>' . $dados['frete']['tipo'] . '</type>'; // 1=PAC, 2=SEDEX, 3=Não especificado
            $xml .= '<cost>' . number_format($dados['frete']['valor'], 2, '.', '') . '</cost>';
            $xml .= '<address>';
            $xml .= '<street>' . $dados['endereco']['rua'] . '</street>';
            $xml .= '<number>' . $dados['endereco']['numero'] . '</number>';
            $xml .= '<complement>' . (isset($dados['endereco']['complemento']) ? $dados['endereco']['complemento'] : '') . '</complement>';
            $xml .= '<district>' . $dados['endereco']['bairro'] . '</district>';
            $xml .= '<city>' . $dados['endereco']['cidade'] . '</city>';
            $xml .= '<state>' . $dados['endereco']['estado'] . '</state>';
            $xml .= '<country>BRA</country>';
            $xml .= '<postalCode>' . $dados['endereco']['cep'] . '</postalCode>';
            $xml .= '</address>';
            $xml .= '</shipping>';
        }
        
        // Dados do cartão de crédito
        if ($dados['metodo'] == 'creditCard' && isset($dados['cartao'])) {
            $xml .= '<creditCard>';
            $xml .= '<token>' . $dados['cartao']['token'] . '</token>';
            $xml .= '<installment>';
            $xml .= '<quantity>' . $dados['cartao']['parcelas'] . '</quantity>';
            $xml .= '<value>' . number_format($dados['cartao']['valor_parcela'], 2, '.', '') . '</value>';
            $xml .= '</installment>';
            $xml .= '<holder>';
            $xml .= '<name>' . $dados['cartao']['nome_titular'] . '</name>';
            $xml .= '<birthDate>' . $dados['cartao']['data_nascimento'] . '</birthDate>';
            $xml .= '<documents>';
            $xml .= '<document>';
            $xml .= '<type>CPF</type>';
            $xml .= '<value>' . $dados['cartao']['cpf_titular'] . '</value>';
            $xml .= '</document>';
            $xml .= '</documents>';
            $xml .= '<phone>';
            $xml .= '<areaCode>' . $dados['ddd'] . '</areaCode>';
            $xml .= '<number>' . $dados['telefone'] . '</number>';
            $xml .= '</phone>';
            $xml .= '</holder>';
            $xml .= '<billingAddress>';
            $xml .= '<street>' . $dados['endereco']['rua'] . '</street>';
            $xml .= '<number>' . $dados['endereco']['numero'] . '</number>';
            $xml .= '<complement>' . (isset($dados['endereco']['complemento']) ? $dados['endereco']['complemento'] : '') . '</complement>';
            $xml .= '<district>' . $dados['endereco']['bairro'] . '</district>';
            $xml .= '<city>' . $dados['endereco']['cidade'] . '</city>';
            $xml .= '<state>' . $dados['endereco']['estado'] . '</state>';
            $xml .= '<country>BRA</country>';
            $xml .= '<postalCode>' . $dados['endereco']['cep'] . '</postalCode>';
            $xml .= '</billingAddress>';
            $xml .= '</creditCard>';
        }
        
        $xml .= '<notificationURL>' . base_url('pagseguro/notificacao') . '</notificationURL>';
        $xml .= '</payment>';

        $params = http_build_query(array(
            'email' => $this->email,
            'token' => $this->token
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml; charset=UTF-8'));

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $xml_response = simplexml_load_string($response);
            
            if ($xml_response && isset($xml_response->code)) {
                return array(
                    'success' => true,
                    'code' => (string) $xml_response->code,
                    'reference' => (string) $xml_response->reference,
                    'url' => $this->url_checkout . (string) $xml_response->code
                );
            }
        }

        return false;
    }

    /**
     * Criar checkout com redirecionamento
     * @param array $dados
     * @return string|false URL de redirecionamento ou false
     */
    public function criar_checkout($dados)
    {
        if (!$this->email || !$this->token) {
            return false;
        }

        $url = $this->url_api . '/v2/checkout';

        $params = array(
            'email' => $this->email,
            'token' => $this->token,
            'currency' => 'BRL',
            'senderName' => $dados['nome'],
            'senderEmail' => $dados['email'],
            'senderAreaCode' => $dados['ddd'],
            'senderPhone' => $dados['telefone'],
        );

        // Adicionar itens
        foreach ($dados['itens'] as $index => $item) {
            $i = $index + 1;
            $params['itemId' . $i] = $item['id'];
            $params['itemDescription' . $i] = $item['descricao'];
            $params['itemQuantity' . $i] = $item['quantidade'];
            $params['itemAmount' . $i] = number_format($item['valor'], 2, '.', '');
        }

        // Adicionar frete se houver
        if (isset($dados['frete'])) {
            $params['shippingType'] = $dados['frete']['tipo'];
            $params['shippingCost'] = number_format($dados['frete']['valor'], 2, '.', '');
            $params['shippingAddressStreet'] = $dados['endereco']['rua'];
            $params['shippingAddressNumber'] = $dados['endereco']['numero'];
            $params['shippingAddressComplement'] = isset($dados['endereco']['complemento']) ? $dados['endereco']['complemento'] : '';
            $params['shippingAddressDistrict'] = $dados['endereco']['bairro'];
            $params['shippingAddressPostalCode'] = $dados['endereco']['cep'];
            $params['shippingAddressCity'] = $dados['endereco']['cidade'];
            $params['shippingAddressState'] = $dados['endereco']['estado'];
            $params['shippingAddressCountry'] = 'BRA';
        }

        $params['redirectURL'] = base_url('pagseguro/retorno');
        $params['notificationURL'] = base_url('pagseguro/notificacao');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $xml = simplexml_load_string($response);
            if ($xml && isset($xml->code)) {
                return $this->url_checkout . (string) $xml->code;
            }
        }

        return false;
    }

    /**
     * Consultar transação
     * @param string $transaction_code
     * @return array|false
     */
    public function consultar_transacao($transaction_code)
    {
        if (!$this->email || !$this->token) {
            return false;
        }

        $url = $this->url_api . '/v3/transactions/' . $transaction_code;
        $params = http_build_query(array(
            'email' => $this->email,
            'token' => $this->token
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $xml = simplexml_load_string($response);
            
            if ($xml) {
                return array(
                    'code' => (string) $xml->code,
                    'reference' => (string) $xml->reference,
                    'status' => (string) $xml->status,
                    'payment_method' => (string) $xml->paymentMethod->type,
                    'gross_amount' => (string) $xml->grossAmount,
                    'discount_amount' => (string) $xml->discountAmount,
                    'net_amount' => (string) $xml->netAmount,
                    'extra_amount' => (string) $xml->extraAmount,
                    'installment_count' => (string) $xml->installmentCount,
                );
            }
        }

        return false;
    }

    /**
     * Processar notificação do PagSeguro
     * @param string $notification_code
     * @return array|false
     */
    public function processar_notificacao($notification_code)
    {
        if (!$this->email || !$this->token) {
            return false;
        }

        $url = $this->url_api . '/v3/transactions/notifications/' . $notification_code;
        $params = http_build_query(array(
            'email' => $this->email,
            'token' => $this->token
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $xml = simplexml_load_string($response);
            
            if ($xml) {
                return array(
                    'code' => (string) $xml->code,
                    'reference' => (string) $xml->reference,
                    'status' => (string) $xml->status,
                    'payment_method' => (string) $xml->paymentMethod->type,
                    'gross_amount' => (string) $xml->grossAmount,
                    'net_amount' => (string) $xml->netAmount,
                );
            }
        }

        return false;
    }

    /**
     * Obter status descritivo da transação
     * @param int $status_code
     * @return string
     */
    public function get_status_descricao($status_code)
    {
        $status = array(
            1 => 'Aguardando pagamento',
            2 => 'Em análise',
            3 => 'Paga',
            4 => 'Disponível',
            5 => 'Em disputa',
            6 => 'Devolvida',
            7 => 'Cancelada',
        );

        return isset($status[$status_code]) ? $status[$status_code] : 'Desconhecido';
    }
}
