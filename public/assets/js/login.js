/**
 * JavaScript para página de login
 */
(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        
        // Validação do formulário de login
        const formLogin = document.getElementById('form_login');
        
        if (formLogin) {
            formLogin.addEventListener('submit', function(e) {
                const email = document.getElementById('email');
                const password = document.getElementById('password');
                let hasError = false;
                
                // Remove mensagens de erro anteriores
                removeErrors();
                
                // Validar email
                if (email.value.trim() === '') {
                    showError(email, 'O campo E-mail ou Usuário é obrigatório');
                    hasError = true;
                }
                
                // Validar senha
                if (password.value.trim() === '') {
                    showError(password, 'O campo Senha é obrigatório');
                    hasError = true;
                }
                
                if (hasError) {
                    e.preventDefault();
                }
            });
        }
        
        // Validação do formulário de recuperar senha
        const formEsqueciSenha = document.getElementById('form_esqueci_senha');
        
        if (formEsqueciSenha) {
            formEsqueciSenha.addEventListener('submit', function(e) {
                const email = document.getElementById('email');
                let hasError = false;
                
                // Remove mensagens de erro anteriores
                removeErrors();
                
                // Validar email
                if (email.value.trim() === '') {
                    showError(email, 'O campo E-mail é obrigatório');
                    hasError = true;
                } else if (!isValidEmail(email.value)) {
                    showError(email, 'Digite um e-mail válido');
                    hasError = true;
                }
                
                if (hasError) {
                    e.preventDefault();
                }
            });
        }
        
        // Função para exibir erro
        function showError(element, message) {
            element.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-danger';
            errorDiv.textContent = message;
            element.parentNode.appendChild(errorDiv);
        }
        
        // Função para remover erros
        function removeErrors() {
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(function(input) {
                input.classList.remove('is-invalid');
            });
            
            const errors = document.querySelectorAll('.text-danger');
            errors.forEach(function(error) {
                if (error.parentNode && error.textContent !== 'Atenção!') {
                    error.remove();
                }
            });
        }
        
        // Função para validar email
        function isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
        
        // Adicionar efeito de focus nos inputs
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(function(input) {
            input.addEventListener('focus', function() {
                this.parentNode.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (this.value === '') {
                    this.parentNode.classList.remove('focused');
                }
            });
        });
    });
})();
