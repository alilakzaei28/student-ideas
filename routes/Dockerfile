# استفاده از تصویر رسمی PHP با آپاچی
FROM php:8.2-apache

# نصب متعلقات مورد نیاز سیستم
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev

# پاکسازی کش
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# نصب اکستنشن‌های مورد نیاز PHP برای لاراول
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# فعال سازی mod_rewrite برای آپاچی (حیاتی برای لاراول)
RUN a2enmod rewrite

# نصب Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تنظیم دایرکتوری کاری
WORKDIR /var/www/html

# کپی کردن کل پروژه به کانتینر
COPY . .

# نصب پکیج‌های PHP
RUN composer install --no-dev --optimize-autoloader

# تنظیمات سطح دسترسی برای پوشه‌های لاراول
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# تغییر ریشه آپاچی به پوشه public لاراول
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# پورت مورد نظر
EXPOSE 80

# شروع آپاچی در پس‌زمینه
CMD ["apache2-foreground"]