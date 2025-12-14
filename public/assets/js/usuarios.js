/**
 * JavaScript para gerenciamento de usuários
 */
(function() {
    'use strict';
    
    // Criar o modal dinamicamente
    function criarModal() {
        const modalHTML = `
            <div class="modal fade" id="modalDeleteUsuario" tabindex="-1" role="dialog" aria-labelledby="modalDeleteUsuarioLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDeleteUsuarioLabel">Confirmar Exclusão</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Tem certeza que deseja excluir o usuário <strong id="nomeUsuario"></strong>?</p>
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
        
        // Selecionar todos os botões de excluir
        const btnDelete = document.querySelectorAll('.btn-delete');
        
        if (btnDelete.length > 0) {
            btnDelete.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const usuarioId = this.getAttribute('data-id');
                    const usuarioNome = this.getAttribute('data-nome');
                    const baseUrl = window.location.origin + '/loja_virtual/restrita/usuarios/delete/';
                    
                    // Definir o nome do usuário no modal
                    document.getElementById('nomeUsuario').textContent = usuarioNome;
                    
                    // Definir o link de confirmação
                    document.getElementById('btnConfirmarExclusao').href = baseUrl + usuarioId;
                    
                    // Abrir o modal
                    $('#modalDeleteUsuario').modal('show');
                });
            });
        }
    });
})();
