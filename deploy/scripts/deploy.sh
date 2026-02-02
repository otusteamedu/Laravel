#!/bin/bash

 cd /app/deploy/scripts

 if [ "$(sudo docker ps -q -f name=blue)" ]; then
     echo "Container 'blue' is running."
     CURRENT=green PREV=blue bash ./_deploy-general.sh
 else
     echo "Container 'blue' is not running."
     CURRENT=blue PREV=green bash ./_deploy-general.sh
 fi

