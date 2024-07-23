# Escolher a imagem oficial do PHP com Apache que suporta PHP 8.2
FROM php:8.2-apache

# Instalar dependências necessárias para o Python e outras extensões do PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    postgresql-client \
    python-is-python3 \
    python3-pip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_pgsql

# Ativar o Apache mod_rewrite
RUN a2enmod rewrite

# Copiar a aplicação para o container
COPY . /var/www/html

# Configurar a pasta raiz do Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Atualizar a configuração do Apache para mudar a raiz do documento
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar e copiar o script de entrada
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expor a porta 80 para acessar o Apache
EXPOSE 80

# Usar o script de entrada como comando inicial
CMD ["entrypoint.sh"]