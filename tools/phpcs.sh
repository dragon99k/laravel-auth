#!/usr/bin/env bash

ROOT_DIR=`pwd`
# ROOT_DIR=/home/runner/work/shino.shinobiz-server/shino.shinobiz-server
path=''

if [ "$#" -ge 1 ]; then
  if [ $1 = '--fix' ]; then
    if [ "$#" -eq 2 ]; then
      path=$2
    fi

    path=$(echo $ROOT_DIR/"${path##*( )}" | sed  "s:^.*server/::")

    echo "apply ${path}"

    bash -c \
      "vendor/bin/php-cs-fixer fix --path-mode=intersection -vv --diff ${path} \
      && vendor/bin/phpcbf --report=full --standard=phpcs.xml --extensions=php ${path} \
      && vendor/bin/phpmd ${path} text ruleset.xml --suffixes php --exclude node_modules,resources,storage,vendor"

    exit 0
  else
    path=$1
  fi
fi

path=$(echo $ROOT_DIR"${path##*( )}" | sed  "s:^.*server/::")

echo "apply ${path}"

bash -c \
  "vendor/bin/php-cs-fixer fix --path-mode=intersection -vv --diff --dry-run ${path} \
  && vendor/bin/phpcs --report=full --standard=phpcs.xml --extensions=php ${path} \
  && vendor/bin/phpmd ${path} text ruleset.xml --suffixes php --exclude node_modules,resources,storage,vendor"
