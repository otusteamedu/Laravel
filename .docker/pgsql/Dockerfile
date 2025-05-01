FROM postgres:15.7-alpine3.20

ARG DOCKER_UID
ARG DOCKER_GID
ARG ALPINE_PREINSTALL_PACKAGES="bash ca-certificates curl git mc nano openssh-client-default openssh-keygen openssl shadow tar tzdata unzip xz"

ENV DOCKER_UID=${DOCKER_UID?:err}
ENV DOCKER_GID=${DOCKER_GID?:err}
ENV WORKDIR="/var/lib/postgresql/data"

# init install and fixups
RUN /bin/bash -c set -ex && \
apk update && apk upgrade && apk add --no-cache ${ALPINE_PREINSTALL_PACKAGES} && \
sed -i -E 's|/bin/[a]?sh|/bin/bash|g' /etc/passwd

# add docker user and group
RUN /bin/bash -c set -ex && \
groupadd -r -o -g ${DOCKER_GID} docker && \
useradd -r -m -o -u ${DOCKER_UID} -g docker -s /bin/bash docker && \
echo 'source /etc/profile' >> /root/.bashrc && \
su docker - -c "source /etc/profile && \
echo 'source /etc/profile' >> /home/docker/.bashrc"

WORKDIR ${WORKDIR}

COPY ./.docker/pgsql/etc/. /etc/

CMD ["postgres"]
