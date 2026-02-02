#!/bin/bash

 if [ -z "$CURRENT" ]; then
      echo "Please set CURRENT variable"
      exit 1
 fi

 if [ -z "$PREV" ]; then
      echo "Please set PREV variable"
      exit 1
 fi

 function startPrevRelease {
      cd $DEPLOY_DIR
      echo "Starting previous container: ${PREV}"
      sudo docker compose up -d $PREV ${PREV}_nginx

      sleep 10

      sudo docker exec --user root gateway sh -c "echo \"set server blue_green/${PREV} state ready\" | socat stdio unix-connect:/sock/admin.sock"
 }

 function stopCurrentRelease {
      cd $DEPLOY_DIR
      sudo docker exec --user root gateway sh -c "echo \"set server blue_green/${CURRENT} state maint\" | socat stdio unix-connect:/sock/admin.sock"

      sleep 10

      sudo docker compose stop $CURRENT ${CURRENT}_nginx
 }

 startPrevRelease
 stopCurrentRelease

