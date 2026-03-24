FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libxml2-dev \
    curl \
    unzip \
    git

# extensiones
RUN docker-php-ext-install dom
RUN docker-php-ext-install xml
RUN docker-php-ext-install pdo pdo_mysql

# instalar composer manualmente 
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /app