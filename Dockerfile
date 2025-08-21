FROM dpokidov/imagemagick:latest

LABEL org.opencontainers.image.authors="Montala Ltd"

ENV DEBIAN_FRONTEND="noninteractive"

# Add Sury PHP repo (Bullseye) and install PHP 8.3 + modules
RUN apt-get update && apt-get install -y --no-install-recommends \
      ca-certificates curl lsb-release \
    && curl -fsSL -o /tmp/debsuryorg-archive-keyring.deb https://packages.sury.org/debsuryorg-archive-keyring.deb \
    && dpkg -i /tmp/debsuryorg-archive-keyring.deb \
    && echo "deb [signed-by=/usr/share/keyrings/debsuryorg-archive-keyring.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" \
         > /etc/apt/sources.list.d/php.list \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
      nano \
      apache2 \
      subversion \
      ghostscript \
      antiword \
      poppler-utils \
      libimage-exiftool-perl \
      cron \
      postfix \
      wget \
      php8.3 php8.3-cli \
      php8.3-curl \
      php8.3-dev \
      php8.3-gd \
      php8.3-intl \
      php8.3-mysql \
      php8.3-mbstring \
      php8.3-zip \
      libapache2-mod-php8.3 \
      ffmpeg \
      libopencv-dev \
      python3-opencv \
      python3 \
      python3-pip \
      vim \
    # (Optional) APCu: try versioned first, fall back to unversioned if needed
    && (apt-get install -y php8.3-apcu || apt-get install -y php-apcu || true) \
    # Enable PHP 8.3 in Apache; disable older PHP if present
    && a2dismod php7.4 >/dev/null 2>&1 || true \
    && a2enmod php8.3 \
    # libapache2-mod-php prefers prefork over event
    && a2dismod mpm_event && a2enmod mpm_prefork \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# PHP 8.3 Apache php.ini tweaks
RUN sed -i -e "s/^upload_max_filesize\s*=.*/upload_max_filesize = 100M/" /etc/php/8.3/apache2/php.ini \
 && sed -i -e "s/^post_max_size\s*=.*/post_max_size = 100M/" /etc/php/8.3/apache2/php.ini \
 && sed -i -e "s/^max_execution_time\s*=.*/max_execution_time = 300/" /etc/php/8.3/apache2/php.ini \
 && sed -i -e "s/^memory_limit\s*=.*/memory_limit = 1G/" /etc/php/8.3/apache2/php.ini

# Loosen directory options a bit (as in your original)
RUN printf '<Directory /var/www/>\n\tOptions FollowSymLinks\n</Directory>\n' \
>> /etc/apache2/sites-enabled/000-default.conf

ADD cronjob /etc/cron.daily/resourcespace
RUN chmod +x /etc/cron.daily/resourcespace

WORKDIR /var/www/html

# && svn co -q https://svn.resourcespace.com/svn/rs/trunk/ . \

RUN rm -f index.html \
 && mkdir -p filestore \
 && chmod 777 filestore 
# && chmod -R 777 include/

# Redirect Apache logs to container output
RUN ln -sf /dev/stdout /var/log/apache2/access.log \
 && ln -sf /dev/stderr /var/log/apache2/error.log

ENTRYPOINT []

CMD apachectl -D FOREGROUND

