# Scaffolder

```shell
docker run \
  --volume .:/project \
  --user $(id -u):$(id -g) \
  --interactive --tty --rm \
  --pull always \
  ghcr.io/phpyh/scaffolder:latest \
  --user-name-default "$(git config user.name 2>/dev/null || whoami 2>/dev/null)" \
  --user-email-default "$(git config user.email 2>/dev/null)" \
  --package-project-default "$(basename $(pwd))"
```
