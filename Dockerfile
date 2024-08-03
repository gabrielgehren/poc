# Use a imagem oficial do PHP 8.0 com Apache
FROM php:8.0-apache

# Instale as dependências necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Habilite módulos do PHP
RUN docker-php-ext-install pdo pdo_mysql

# Instale o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Copie o código fonte para o contêiner
COPY . .

# Configure o Apache
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Exponha a porta 80
EXPOSE 80

# Comando para iniciar o Apache
CMD ["apache2-foreground"]
