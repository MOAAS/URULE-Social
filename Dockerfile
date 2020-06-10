FROM ubuntu:18.04

# Install dependencies
RUN apt-get update
RUN apt-get install -y --no-install-recommends libpq-dev vim nginx php7.2-fpm php7.2-mbstring php7.2-xml php7.2-pgsql php7.2-curl php7.2-gd
RUN apt-get install -y bc

# Installing cron package
#RUN apt-get install -y cron
#RUN touch /var/log/schedule.log
#RUN chmod 0777 /var/log/schedule.log
#ADD scheduler/crontab /etc/cron.d/scheduler
#RUN crontab /etc/cron.d/scheduler
#CMD ["cron", "-f"]

# Copy project code and install project dependencies
COPY . /var/www/
RUN mkdir -p /etc/ssl/certs/
RUN cp /var/www/etc/php/cacert.pem /etc/ssl/certs/ca-certificates.crt
RUN rm -rf /var/www/node_modules/
RUN chown -R www-data:www-data /etc/ssl/certs/
RUN chown -R www-data:www-data /var/www/

# Copy project configurations
COPY ./etc/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./etc/nginx/default.conf /etc/nginx/sites-enabled/default
#COPY ./etc/docker/daemon.json /etc/docker/daemon.json
COPY .env_production /var/www/.env
COPY docker_run.sh /docker_run.sh
COPY schedule_runner.sh /schedule_runner.sh
RUN mkdir /var/run/php

# Start command
CMD sh /docker_run.sh
