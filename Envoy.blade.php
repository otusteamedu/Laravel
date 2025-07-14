@servers(['web' => '192.168.0.194'])

@story('deploy')
initVars
check
@endstory

@task('initVars')
if [ "$(sudo docker ps -q -f name=blue)" ]; then
echo "Container 'blue' is running."
export CURRENT=green
export PREV=blue
bash ./_deploy-general.sh
else
echo "Container 'blue' is not running."
export CURRENT=blue
export PREV=green
bash ./_deploy-general.sh
fi
@endtask

@task('check')
echo $CURRENT
@endtask

@task('goApp')
cd /home/deploy/apps/site/releases/$CURRENT
@endtask

@task('downloadNewCode')
git fetch origin prod
git checkout prod
git reset --hard origin/prod
@endtask

@task('buildApp')
composer install
/home/deploy/.bun/bin/bun install
/home/deploy/.bun/bin/bun run build
@endtask

@task('goDockerHome')
cd /home/deploy/apps/site
@endtask

@task('startCurrentRelease')
sudo docker compose up -d $CURRENT ${CURRENT}_nginx
optimizeResources
sleep 10
sudo docker exec -it gateway sh -c "echo \"set server blue_green/${CURRENT} state ready\" | socat stdio \
unix-connect:/sock/admin.sock"
@endtask

@task('stopPrevRelease')
sudo docker exec -it gateway sh -c "echo \"set server blue_green/${PREV} state maint\" | socat stdio \
unix-connect:/sock/admin.sock"
sleep 10
sudo docker compose stop $PREV ${PREV}_nginx
@endtask
