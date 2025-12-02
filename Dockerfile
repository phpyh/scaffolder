FROM ghcr.io/phpyh/php:8.4

WORKDIR /scaffolder

COPY . ./

RUN --mount=type=cache,target=${COMPOSER_CACHE_DIR},uid=${UID},gid=${GID} <<EOF
    set -eux
    composer install --no-dev --classmap-authoritative
EOF

ENTRYPOINT ["tini", "--", "php", "/scaffolder/src/run.php"]
