/**
 * JavaScript para formulário de categorias
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
    
    // Aguardar o carregamento do DOM
    document.addEventListener('DOMContentLoaded', function() {
        
        const inputNome = document.getElementById('inputCategoriaNome');
        const inputMetaLink = document.getElementById('inputCategoriaMetaLink');
        
        if (inputNome && inputMetaLink) {
            // Gerar slug ao digitar o nome
            inputNome.addEventListener('input', function() {
                const slug = gerarSlug(this.value);
                inputMetaLink.value = slug;
            });
            
            // Se estiver editando e o campo nome for alterado, atualizar o slug
            inputNome.addEventListener('keyup', function() {
                const slug = gerarSlug(this.value);
                inputMetaLink.value = slug;
            });
        }
    });
})();
