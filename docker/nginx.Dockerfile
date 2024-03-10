FROM nginx:1.19-alpine AS nginx
COPY /docker/vhost.conf /etc/nginx/conf.d/default.conf
COPY / /var/www/html/
