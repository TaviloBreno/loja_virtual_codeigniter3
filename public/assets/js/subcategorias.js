/**
 * JavaScript para deleção de subcategorias
 */
(function() {
    'use strict';
    
    // Criar o modal dinamicamente
    function criarModal() {
        if (!document.getElementById('modalDeleteSubcategoria')) {
            const modalHTML = `
                <div class="modal fade" id="modalDeleteSubcategoria" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalDeleteLabel">Confirmar exclusão</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Deseja realmente excluir a subcategoria <strong id="subcategoriaNome"></strong>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <a href="#" class="btn btn-danger" id="btnConfirmarDelete">Excluir</a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }
    }
    
    // Aguardar o carregamento do DOM
    document.addEventListener('DOMContentLoaded', function() {
        
        // Criar o modal ao carregar a página
        criarModal();
        
        // Usar event delegation para os botões de delete (funciona com DataTables)
        document.addEventListener('click', function(e) {
            const btnDelete = e.target.closest('.btn-delete');
            
            if (btnDelete) {
                e.preventDefault();
                
                const subcategoriaId = btnDelete.getAttribute('data-id');
                const subcategoriaNome = btnDelete.getAttribute('data-nome');
                
                // Atualizar o modal com as informações
                document.getElementById('subcategoriaNome').textContent = subcategoriaNome;
                
                // Construir a URL de delete
                const protocol = window.location.protocol;
                const host = window.location.host;
                const pathname = window.location.pathname;
                
                let baseUrl;
                if (pathname.includes('index.php')) {
                    const pathParts = pathname.split('index.php')[0];
                    baseUrl = protocol + '//' + host + pathParts + 'index.php/restrita/subcategorias/excluir/';
                } else {
                    const pathParts = pathname.split('/restrita')[0];
                    baseUrl = protocol + '//' + host + pathParts + '/restrita/subcategorias/excluir/';
                }
                
                const deleteUrl = baseUrl + subcategoriaId;
                document.getElementById('btnConfirmarDelete').setAttribute('href', deleteUrl);
                
                // Abrir o modal
                $('#modalDeleteSubcategoria').modal('show');
            }
        });
    });
})();
