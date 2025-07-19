FROM haproxy:alpine
USER root
RUN apk add --no-cache socat
RUN mkdir /sock && chown -R haproxy:haproxy /sock
USER haproxy
