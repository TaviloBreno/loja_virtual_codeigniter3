/**
 * JavaScript para formulário de produtos
 */
(function() {
    'use strict';
    
    // Função para gerar slug a partir do texto
    function gerarSlug(texto) {
        return texto
            .toString()
            .toLowerCase()
            .trim()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '') // Remove acentos
            .replace(/[^a-z0-9\s-]/g, '') // Remove caracteres especiais
            .replace(/\s+/g, '-') // Substitui espaços por hífens
            .replace(/-+/g, '-'); // Remove hífens duplicados
    }
    
    // Função para formatar valor monetário
    function formatarMoeda(input) {
        let valor = input.value.replace(/\D/g, '');
        valor = (valor / 100).toFixed(2) + '';
        valor = valor.replace('.', ',');
        valor = valor.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        input.value = valor;
    }
    
    // Aguardar o carregamento do DOM
    document.addEventListener('DOMContentLoaded', function() {
        
        const inputNome = document.getElementById('inputProdutoNome');
        const inputMetaLink = document.getElementById('inputProdutoMetaLink');
        const inputValor = document.getElementById('inputValor');
        const inputControlarEstoque = document.getElementById('inputControlarEstoque');
        const inputQuantidadeEstoque = document.getElementById('inputQuantidadeEstoque');
        
        // Gerar slug ao digitar o nome
        if (inputNome && inputMetaLink) {
            inputNome.addEventListener('input', function() {
                const slug = gerarSlug(this.value);
                inputMetaLink.value = slug;
            });
            
            inputNome.addEventListener('keyup', function() {
                const slug = gerarSlug(this.value);
                inputMetaLink.value = slug;
            });
        }
        
        // Formatar campo de valor
        if (inputValor) {
            inputValor.addEventListener('blur', function() {
                formatarMoeda(this);
            });
        }
        
        // Controlar visibilidade do campo de quantidade em estoque
        if (inputControlarEstoque && inputQuantidadeEstoque) {
            function toggleEstoque() {
                if (inputControlarEstoque.value === '0') {
                    inputQuantidadeEstoque.disabled = true;
                    inputQuantidadeEstoque.value = '0';
                } else {
                    inputQuantidadeEstoque.disabled = false;
                }
            }
            
            inputControlarEstoque.addEventListener('change', toggleEstoque);
            toggleEstoque(); // Executar ao carregar
        }
    });
})();
