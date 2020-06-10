FROM php:7.4-fpm

RUN chmod o+r /etc/resolv.conf

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www


ENV HOME=/home/app
COPY . $HOME/covidprudente/

COPY --chown=www:www . $HOME/covidprudente/

USER www
WORKDIR $HOME/covidprudente

EXPOSE 8001

CMD ["php", "-S", "0.0.0.0:8001", "-t", "public"]