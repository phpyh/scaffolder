FROM php:8.5-cli-alpine

ARG UID=10001
ARG GID=10001

ENV LC_ALL=C.UTF-8

ENV COMPOSER_HOME=/composer

RUN <<EOF
    set -eux
    (curl -sSLf https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o - || echo 'return 1') | sh -s \
        @composer
EOF

RUN <<EOF
    set -eux
    addgroup -g ${GID} dev
    adduser -u ${UID} -G dev -D dev

    chown -R dev:dev ${COMPOSER_HOME}
EOF

USER dev

RUN <<EOF
    set -eux
    composer global config allow-plugins.ergebnis/composer-normalize true
    composer global require --no-cache ergebnis/composer-normalize
EOF

WORKDIR /scaffolder

COPY . ./

RUN <<EOF
    set -eux
    composer install --no-dev --classmap-authoritative
EOF

WORKDIR /project

ENTRYPOINT ["php", "/scaffolder/bin/run.php"]
