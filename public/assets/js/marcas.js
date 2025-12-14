/**
 * JavaScript para gerenciamento de marcas
 */
(function() {
    'use strict';
    
    // Criar o modal dinamicamente
    function criarModal() {
        const modalHTML = `
            <div class="modal fade" id="modalDeleteMarca" tabindex="-1" role="dialog" aria-labelledby="modalDeleteMarcaLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDeleteMarcaLabel">Confirmar Exclusão</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Tem certeza que deseja excluir a marca <strong id="nomeMarca"></strong>?</p>
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
                
                const marcaId = btn.getAttribute('data-id');
                const marcaNome = btn.getAttribute('data-nome');
                
                // Construir URL base a partir da URL atual
                const currentPath = window.location.pathname;
                const baseUrlPath = currentPath.includes('/index.php/') 
                    ? currentPath.split('/index.php/')[0] + '/index.php/restrita/marcas/excluir/'
                    : currentPath.replace(/\/restrita\/marcas.*/, '') + '/index.php/restrita/marcas/excluir/';
                
                const baseUrl = window.location.origin + baseUrlPath;
                
                // Atualizar o conteúdo do modal
                document.getElementById('nomeMarca').textContent = marcaNome;
                document.getElementById('btnConfirmarExclusao').href = baseUrl + marcaId;
                
                // Exibir o modal
                $('#modalDeleteMarca').modal('show');
            }
        });
    });
})();
