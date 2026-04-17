FROM php:8.5-cli-alpine

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

ENV LC_ALL=C.UTF-8

ENV UID=10001
ENV GID=10001

RUN <<EOF
    set -eux
    addgroup -g ${GID} dev
    adduser -u ${UID} -G dev -D dev
EOF

USER dev

WORKDIR /scaffolder

COPY . ./

RUN --mount=type=cache,target=/home/dev/.composer/cache <<EOF
    set -eux
    composer install --no-dev --classmap-authoritative
EOF

WORKDIR /project

ENTRYPOINT ["php", "/scaffolder/bin/run.php"]
