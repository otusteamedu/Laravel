# Laravel
sail artisan migrate:fresh --env=testing
XDEBUG_MODE=coverage sail test --coverage-html coverage-report

# Documentation
sail artisan l5-swagger:generate
URL: /api/documentation/
