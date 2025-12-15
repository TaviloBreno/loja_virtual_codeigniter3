<?php
defined('BASEPATH') OR exit('Ação não permitida');

class Correios {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Consulta CEP via API ViaCEP
     * @param string $cep
     * @return object|false
     */
    public function consulta_cep($cep)
    {
        // Limpar CEP
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) != 8) {
            return false;
        }

        $url = "https://viacep.com.br/ws/{$cep}/json/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return false;
        }

        $data = json_decode($response);

        if (isset($data->erro) && $data->erro) {
            return false;
        }

        return $data;
    }

    /**
     * Calcula frete pelos Correios
     * @param array $params
     * @return array|false
     */
    public function calcula_frete($params)
    {
        // Parâmetros obrigatórios
        $defaults = array(
            'cep_origem' => '',
            'cep_destino' => '',
            'peso' => 0,
            'formato' => 1, // 1=Caixa/Pacote, 2=Rolo/Prisma, 3=Envelope
            'comprimento' => 16,
            'altura' => 2,
            'largura' => 11,
            'diametro' => 0,
            'mao_propria' => 'N',
            'valor_declarado' => 0,
            'aviso_recebimento' => 'N',
        );

        $params = array_merge($defaults, $params);

        // Limpar CEPs
        $params['cep_origem'] = preg_replace('/[^0-9]/', '', $params['cep_origem']);
        $params['cep_destino'] = preg_replace('/[^0-9]/', '', $params['cep_destino']);

        // Validações
        if (strlen($params['cep_origem']) != 8 || strlen($params['cep_destino']) != 8) {
            return false;
        }

        // Peso em gramas, mínimo 300g
        if ($params['peso'] < 300) {
            $params['peso'] = 300;
        }

        // Dimensões mínimas em cm
        if ($params['comprimento'] < 16) $params['comprimento'] = 16;
        if ($params['altura'] < 2) $params['altura'] = 2;
        if ($params['largura'] < 11) $params['largura'] = 11;

        // Serviços: 04014 = SEDEX, 04510 = PAC
        $servicos = array('04014', '04510');
        $resultados = array();

        foreach ($servicos as $servico) {
            $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx";
            $url .= "?nCdEmpresa=";
            $url .= "&sDsSenha=";
            $url .= "&nCdServico=" . $servico;
            $url .= "&sCepOrigem=" . $params['cep_origem'];
            $url .= "&sCepDestino=" . $params['cep_destino'];
            $url .= "&nVlPeso=" . ($params['peso'] / 1000); // Converter para kg
            $url .= "&nCdFormato=" . $params['formato'];
            $url .= "&nVlComprimento=" . $params['comprimento'];
            $url .= "&nVlAltura=" . $params['altura'];
            $url .= "&nVlLargura=" . $params['largura'];
            $url .= "&nVlDiametro=" . $params['diametro'];
            $url .= "&sCdMaoPropria=" . $params['mao_propria'];
            $url .= "&nVlValorDeclarado=" . $params['valor_declarado'];
            $url .= "&sCdAvisoRecebimento=" . $params['aviso_recebimento'];
            $url .= "&StrRetorno=xml";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                $xml = simplexml_load_string($response);
                
                if ($xml && isset($xml->cServico)) {
                    $servico_data = $xml->cServico;
                    
                    $nome_servico = ($servico == '04014') ? 'SEDEX' : 'PAC';
                    
                    $resultados[] = array(
                        'codigo' => (string) $servico_data->Codigo,
                        'nome' => $nome_servico,
                        'valor' => (string) $servico_data->Valor,
                        'prazo_entrega' => (string) $servico_data->PrazoEntrega,
                        'valor_mao_propria' => (string) $servico_data->ValorMaoPropria,
                        'valor_aviso_recebimento' => (string) $servico_data->ValorAvisoRecebimento,
                        'valor_declarado' => (string) $servico_data->ValorValorDeclarado,
                        'erro' => (string) $servico_data->Erro,
                        'msg_erro' => (string) $servico_data->MsgErro,
                    );
                }
            }
        }

        return $resultados;
    }

    /**
     * Calcula prazo de entrega pelos Correios
     * @param array $params
     * @return array|false
     */
    public function consulta_prazo($params)
    {
        $defaults = array(
            'cep_origem' => '',
            'cep_destino' => '',
        );

        $params = array_merge($defaults, $params);

        // Limpar CEPs
        $params['cep_origem'] = preg_replace('/[^0-9]/', '', $params['cep_origem']);
        $params['cep_destino'] = preg_replace('/[^0-9]/', '', $params['cep_destino']);

        if (strlen($params['cep_origem']) != 8 || strlen($params['cep_destino']) != 8) {
            return false;
        }

        // Serviços: 04014 = SEDEX, 04510 = PAC
        $servicos = array('04014', '04510');
        $resultados = array();

        foreach ($servicos as $servico) {
            $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx";
            $url .= "?nCdEmpresa=";
            $url .= "&sDsSenha=";
            $url .= "&nCdServico=" . $servico;
            $url .= "&sCepOrigem=" . $params['cep_origem'];
            $url .= "&sCepDestino=" . $params['cep_destino'];
            $url .= "&StrRetorno=xml";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                $xml = simplexml_load_string($response);
                
                if ($xml && isset($xml->cServico)) {
                    $servico_data = $xml->cServico;
                    
                    $nome_servico = ($servico == '04014') ? 'SEDEX' : 'PAC';
                    
                    $resultados[] = array(
                        'codigo' => (string) $servico_data->Codigo,
                        'nome' => $nome_servico,
                        'prazo_entrega' => (string) $servico_data->PrazoEntrega,
                        'erro' => (string) $servico_data->Erro,
                        'msg_erro' => (string) $servico_data->MsgErro,
                    );
                }
            }
        }

        return $resultados;
    }

    /**
     * Rastrear encomenda pelos Correios
     * @param string $codigo_rastreio
     * @return array|false
     */
    public function rastrear_encomenda($codigo_rastreio)
    {
        // Limpar código
        $codigo_rastreio = strtoupper(trim($codigo_rastreio));

        if (strlen($codigo_rastreio) != 13) {
            return false;
        }

        $url = "https://proxyapp.correios.com.br/v1/sro-rastro/{$codigo_rastreio}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return false;
        }

        $data = json_decode($response, true);

        if (isset($data['objetos']) && !empty($data['objetos'])) {
            return $data['objetos'][0];
        }

        return false;
    }
}
