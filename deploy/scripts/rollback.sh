#!/bin/bash

 cd $DEPLOY_DIR/scripts

 if [ "$(sudo docker ps -q -f name=green)" ]; then
     echo "Container 'green' is running."
     echo "Starting rollback"
     PREV=blue CURRENT=green bash ./_rollback-general.sh
 else
     echo "Container 'green' is not running."
     echo "Starting rollback"
     PREV=green CURRENT=blue bash ./_rollback-general.sh
 fi

