#!/bin/bash

 if [ -z "$CURRENT" ]; then
     echo "Please set CURRENT variable"
     exit 1
 fi

 if [ -z "$PREV" ]; then
     echo "Please set PREV variable"
     exit 1
 fi

 function downloadNewCode {
     sudo rm -rf $DEPLOY_DIR/releases/$CURRENT
     git clone http://gitlab-ci-token:${CI_JOB_TOKEN}@${CI_SERVER_FQDN}/${CI_PROJECT_PATH} $DEPLOY_DIR/releases/$CURRENT
 }

 function buildApp {
     cd $DEPLOY_DIR

     sudo docker compose up -d $CURRENT ${CURRENT}_nginx

     sudo docker exec --user root $CURRENT composer install
     sudo docker exec --user root $CURRENT php artisan migrate
     sudo docker exec --user root $CURRENT chmod a+rwx -R storage
     sudo docker exec --user root $CURRENT chmod a+rwx bootstrap
     sudo docker exec --user root $CURRENT chmod a+rwx -R bootstrap/cache


     cd $DEPLOY_DIR/releases/$CURRENT

     sudo /root/.bun/bin/bun install
     sudo /root/.bun/bin/bun run build
 }

 function optimizeResources {
     cd $DEPLOY_DIR

     sudo docker exec $CURRENT php artisan optimize:clear -vvv
     sudo docker exec $CURRENT php artisan optimize

 }

 function startCurrentRelease {
     cd $DEPLOY_DIR

     sudo docker compose up -d ${CURRENT}_nginx

     sleep 10

     sudo docker exec --user root gateway sh -c "echo \"set server blue_green/${CURRENT} state ready\" | socat stdio unix-connect:/sock/admin.sock"
 }

 function stopPrevRelease {
     cd $DEPLOY_DIR

     sudo docker exec --user root gateway sh -c "echo \"set server blue_green/${PREV} state maint\" | socat stdio unix-connect:/sock/admin.sock"

     sleep 10
     sudo docker compose stop $PREV ${PREV}_nginx
 }

 function changeOwnership {
     sudo chown 1000:1000 -R $DEPLOY_DIR/releases/$CURRENT

 }

 downloadNewCode
 buildApp

 changeOwnership

 optimizeResources

 startCurrentRelease
 stopPrevRelease

