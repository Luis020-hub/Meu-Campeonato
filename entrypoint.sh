#!/bin/bash
# Verifica se o vendor existe e executa composer install se não existir
if [ ! -d "/var/www/html/vendor" ]; then
  composer install --no-interaction --optimize-autoloader --prefer-dist
fi

# Inicia o Apache em primeiro plano
apache2-foreground