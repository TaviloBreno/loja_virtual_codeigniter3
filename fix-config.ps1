Write-Host "Corrigindo config.php..." -ForegroundColor Yellow
docker exec loja_virtual_php sed -i 's/0o644/0644/g' /var/www/html/application/config/config.php
docker exec loja_virtual_php rm -rf /var/www/html/application/cache/*
Write-Host "Config corrigido e cache limpo!" -ForegroundColor Green
