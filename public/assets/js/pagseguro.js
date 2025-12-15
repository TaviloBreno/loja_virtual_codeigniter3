/**
 * JavaScript para integração com PagSeguro
 */
(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        
        const formCheckout = document.getElementById('formCheckout');
        
        if (formCheckout) {
            formCheckout.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Esconder form e mostrar loading
                formCheckout.style.display = 'none';
                document.getElementById('loading').style.display = 'block';
                
                const formData = new FormData(formCheckout);
                
                // Construir URL base
                const protocol = window.location.protocol;
                const host = window.location.host;
                const pathname = window.location.pathname;
                
                let baseUrl;
                if (pathname.includes('index.php')) {
                    const pathParts = pathname.split('index.php')[0];
                    baseUrl = protocol + '//' + host + pathParts + 'index.php/restrita/pagamentos/processar_checkout';
                } else {
                    const pathParts = pathname.split('/restrita')[0];
                    baseUrl = protocol + '//' + host + pathParts + '/restrita/pagamentos/processar_checkout';
                }
                
                fetch(baseUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirecionar para o PagSeguro
                        window.location.href = data.url;
                    } else {
                        alert('Erro: ' + data.message);
                        formCheckout.style.display = 'block';
                        document.getElementById('loading').style.display = 'none';
                    }
                })
                .catch(error => {
                    alert('Erro ao processar checkout. Tente novamente.');
                    formCheckout.style.display = 'block';
                    document.getElementById('loading').style.display = 'none';
                    console.error('Erro:', error);
                });
            });
        }
        
        // Máscara CPF
        const cpfInput = document.getElementById('cpf');
        if (cpfInput) {
            cpfInput.addEventListener('input', function() {
                let valor = this.value.replace(/\D/g, '');
                valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
                valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
                valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                this.value = valor;
            });
        }
        
        // Máscara Telefone
        const telefoneInput = document.getElementById('telefone');
        if (telefoneInput) {
            telefoneInput.addEventListener('input', function() {
                let valor = this.value.replace(/\D/g, '');
                if (valor.length <= 8) {
                    valor = valor.replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    valor = valor.replace(/(\d{5})(\d)/, '$1-$2');
                }
                this.value = valor;
            });
        }
    });
})();
