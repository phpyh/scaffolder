# Scaffolder

```shell
docker run \
  --volume .:/project \
  --user $(id -u):$(id -g) \
  --interactive --tty --rm \
  --pull always \
  ghcr.io/phpyh/scaffolder:latest \
  --user-name-default "$(git config user.name 2>&1 || whoami 2>&1)" \
  --user-email-default "$(git config user.email 2>&1)" \
  --package-project-default "$(basename $(pwd))"
git add --all
```
