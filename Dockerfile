FROM php:8.5-cli-alpine AS builder

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /scaffolder

COPY . ./

RUN --mount=type=cache,target=/root/.composer/cache <<EOF
    set -eux
    composer install --no-dev --classmap-authoritative
EOF

FROM php:8.5-cli-alpine

ENV UID=10001
ENV GID=10001

ENV LC_ALL=C.UTF-8

RUN <<EOF
    set -eux
    addgroup -g ${GID} dev
    adduser -u ${UID} -G dev -D dev
EOF

USER dev

WORKDIR /project

COPY --from=builder --chown=dev:dev /scaffolder /scaffolder

ENTRYPOINT ["php", "/scaffolder/bin/run.php"]
