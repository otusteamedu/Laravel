#!/bin/bash

 function downloadNewCode {
     sudo rm -rf $DEPLOY_DIR/releases/test
     git clone http://gitlab-ci-token:${CI_JOB_TOKEN}@${CI_SERVER_FQDN}/${CI_PROJECT_PATH} $DEPLOY_DIR/releases/test
 }

 function buildApp {
     cd $DEPLOY_DIR

     sudo docker compose up -d test
     sudo docker exec --user root test composer install

     sudo docker exec --user root test php artisan migrate

     cd $DEPLOY_DIR/releases/test

     sudo /root/.bun/bin/bun install
     sudo /root/.bun/bin/bun run build
 }

 function runTests {
     sudo docker exec --user root test php artisan test
 }

 function stopTestsContainer {
     cd $DEPLOY_DIR

     sudo docker compose down test test_mysql --remove-orphans
 }

 downloadNewCode
 buildApp

 runTests
 stopTestsContainer

