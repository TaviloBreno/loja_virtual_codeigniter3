/**
 * JavaScript para cálculo de frete e rastreamento
 */
(function() {
    'use strict';
    
    // Máscara para CEP
    function mascaraCEP(input) {
        let valor = input.value.replace(/\D/g, '');
        valor = valor.replace(/^(\d{5})(\d)/, '$1-$2');
        input.value = valor;
    }
    
    // Formatar valor monetário
    function formatarMoeda(valor) {
        valor = parseFloat(valor.replace(',', '.'));
        return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        
        const formFrete = document.getElementById('formFrete');
        const formRastreio = document.getElementById('formRastreio');
        const cepOrigemInput = document.getElementById('cep_origem');
        const cepDestinoInput = document.getElementById('cep_destino');
        
        // Aplicar máscara nos CEPs
        if (cepOrigemInput) {
            cepOrigemInput.addEventListener('input', function() {
                mascaraCEP(this);
            });
        }
        
        if (cepDestinoInput) {
            cepDestinoInput.addEventListener('input', function() {
                mascaraCEP(this);
            });
        }
        
        // Calcular Frete
        if (formFrete) {
            formFrete.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Esconder resultados anteriores
                document.getElementById('resultadoFrete').style.display = 'none';
                document.getElementById('erroFrete').style.display = 'none';
                document.getElementById('loadingFrete').style.display = 'block';
                
                const formData = new FormData(formFrete);
                
                // Construir URL base
                const protocol = window.location.protocol;
                const host = window.location.host;
                const pathname = window.location.pathname;
                
                let baseUrl;
                if (pathname.includes('index.php')) {
                    const pathParts = pathname.split('index.php')[0];
                    baseUrl = protocol + '//' + host + pathParts + 'index.php/restrita/frete/calcular';
                } else {
                    const pathParts = pathname.split('/restrita')[0];
                    baseUrl = protocol + '//' + host + pathParts + '/restrita/frete/calcular';
                }
                
                fetch(baseUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loadingFrete').style.display = 'none';
                    
                    if (data.success) {
                        exibirResultadoFrete(data.data);
                    } else {
                        document.getElementById('mensagemErro').textContent = data.message;
                        document.getElementById('erroFrete').style.display = 'block';
                    }
                })
                .catch(error => {
                    document.getElementById('loadingFrete').style.display = 'none';
                    document.getElementById('mensagemErro').textContent = 'Erro ao calcular frete. Tente novamente.';
                    document.getElementById('erroFrete').style.display = 'block';
                    console.error('Erro:', error);
                });
            });
        }
        
        // Exibir resultado do frete
        function exibirResultadoFrete(fretes) {
            const container = document.getElementById('resultadoCards');
            container.innerHTML = '';
            
            fretes.forEach(function(frete) {
                if (frete.erro == '0') {
                    const card = `
                        <div class="col-md-6 mb-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">
                                        <i class="fas fa-truck"></i> ${frete.nome}
                                    </h5>
                                    <hr>
                                    <p class="card-text">
                                        <strong>Valor:</strong> ${formatarMoeda(frete.valor)}<br>
                                        <strong>Prazo de entrega:</strong> ${frete.prazo_entrega} dia(s) úteis<br>
                                        ${frete.valor_mao_propria > 0 ? '<strong>Mão própria:</strong> ' + formatarMoeda(frete.valor_mao_propria) + '<br>' : ''}
                                        ${frete.valor_aviso_recebimento > 0 ? '<strong>Aviso de recebimento:</strong> ' + formatarMoeda(frete.valor_aviso_recebimento) + '<br>' : ''}
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += card;
                } else {
                    const cardErro = `
                        <div class="col-md-6 mb-3">
                            <div class="card border-danger">
                                <div class="card-body">
                                    <h5 class="card-title text-danger">
                                        <i class="fas fa-exclamation-triangle"></i> ${frete.nome}
                                    </h5>
                                    <hr>
                                    <p class="card-text text-danger">
                                        ${frete.msg_erro}
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += cardErro;
                }
            });
            
            document.getElementById('resultadoFrete').style.display = 'block';
        }
        
        // Rastrear Encomenda
        if (formRastreio) {
            formRastreio.addEventListener('submit', function(e) {
                e.preventDefault();
                
                document.getElementById('resultadoRastreio').style.display = 'none';
                document.getElementById('loadingRastreio').style.display = 'block';
                
                const formData = new FormData(formRastreio);
                
                const protocol = window.location.protocol;
                const host = window.location.host;
                const pathname = window.location.pathname;
                
                let baseUrl;
                if (pathname.includes('index.php')) {
                    const pathParts = pathname.split('index.php')[0];
                    baseUrl = protocol + '//' + host + pathParts + 'index.php/restrita/frete/rastrear';
                } else {
                    const pathParts = pathname.split('/restrita')[0];
                    baseUrl = protocol + '//' + host + pathParts + '/restrita/frete/rastrear';
                }
                
                fetch(baseUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loadingRastreio').style.display = 'none';
                    
                    if (data.success) {
                        exibirRastreamento(data.data);
                    } else {
                        document.getElementById('resultadoRastreio').innerHTML = `
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-triangle"></i> ${data.message}
                            </div>
                        `;
                        document.getElementById('resultadoRastreio').style.display = 'block';
                    }
                })
                .catch(error => {
                    document.getElementById('loadingRastreio').style.display = 'none';
                    document.getElementById('resultadoRastreio').innerHTML = `
                        <div class="alert alert-danger mt-3">
                            <i class="fas fa-exclamation-triangle"></i> Erro ao rastrear encomenda.
                        </div>
                    `;
                    document.getElementById('resultadoRastreio').style.display = 'block';
                    console.error('Erro:', error);
                });
            });
        }
        
        // Exibir resultado do rastreamento
        function exibirRastreamento(rastreio) {
            let html = '<div class="alert alert-success mt-3">';
            html += '<h5><i class="fas fa-check-circle"></i> Encomenda Encontrada</h5>';
            
            if (rastreio.eventos && rastreio.eventos.length > 0) {
                html += '<div class="timeline mt-3">';
                rastreio.eventos.forEach(function(evento, index) {
                    html += `
                        <div class="timeline-item ${index === 0 ? 'timeline-item-primary' : ''}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <strong>${evento.descricao}</strong><br>
                                <small>${evento.dtHrCriado}</small><br>
                                ${evento.unidade ? '<small>' + evento.unidade.nome + ' - ' + evento.unidade.endereco.cidade + '/' + evento.unidade.endereco.uf + '</small>' : ''}
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
            }
            
            html += '</div>';
            
            document.getElementById('resultadoRastreio').innerHTML = html;
            document.getElementById('resultadoRastreio').style.display = 'block';
        }
    });
})();
