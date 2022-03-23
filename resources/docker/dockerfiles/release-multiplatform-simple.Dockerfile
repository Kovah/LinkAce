# DOCKERFILE RELEASE
FROM linkace/linkace:latest

# Install nginx and supervisor
RUN apk add --no-cache nginx supervisor

# Configure Supervisor for nginx
RUN mkdir /etc/supervisor.d/; \
	mkdir -p /run/nginx; \
	mkdir /ssl-certs ; \
	ln -sf /dev/stdout /var/log/nginx/access.log ; \
	ln -sf /dev/stderr /var/log/nginx/error.log
COPY ./resources/docker/supervisord.ini /etc/supervisor.d/supervisord.ini

# Copy the nginx config file
COPY ./resources/docker/nginx/nginx.conf /etc/nginx/http.d/default.conf

EXPOSE 80 443
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor.d/supervisord.ini"]
