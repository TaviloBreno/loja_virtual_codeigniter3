/**
 * JavaScript para gerenciamento de categorias
 */
(function() {
    'use strict';
    
    // Criar o modal dinamicamente
    function criarModal() {
        const modalHTML = `
            <div class="modal fade" id="modalDeleteCategoria" tabindex="-1" role="dialog" aria-labelledby="modalDeleteCategoriaLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDeleteCategoriaLabel">Confirmar Exclusão</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Tem certeza que deseja excluir a categoria <strong id="nomeCategoria"></strong>?</p>
                            <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> Esta ação não pode ser desfeita!</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <a href="#" id="btnConfirmarExclusao" class="btn btn-danger">Sim, Excluir</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Adicionar o modal ao body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }
    
    // Aguardar o carregamento do DOM
    document.addEventListener('DOMContentLoaded', function() {
        
        // Criar o modal
        criarModal();
        
        // Usar delegação de eventos para funcionar em todas as páginas do DataTable
        document.addEventListener('click', function(e) {
            // Verificar se o elemento clicado ou seu pai tem a classe btn-delete
            const btn = e.target.closest('.btn-delete');
            
            if (btn) {
                e.preventDefault();
                
                const categoriaId = btn.getAttribute('data-id');
                const categoriaNome = btn.getAttribute('data-nome');
                
                // Construir URL base a partir da URL atual
                const currentPath = window.location.pathname;
                const baseUrlPath = currentPath.includes('/index.php/') 
                    ? currentPath.split('/index.php/')[0] + '/index.php/restrita/categorias/excluir/'
                    : currentPath.replace(/\/restrita\/categorias.*/, '') + '/index.php/restrita/categorias/excluir/';
                
                const baseUrl = window.location.origin + baseUrlPath;
                
                // Atualizar o conteúdo do modal
                document.getElementById('nomeCategoria').textContent = categoriaNome;
                document.getElementById('btnConfirmarExclusao').href = baseUrl + categoriaId;
                
                // Exibir o modal
                $('#modalDeleteCategoria').modal('show');
            }
        });
    });
})();
