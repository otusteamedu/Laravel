#!/bin/bash

while [ true ]
    do
		sleep 60 &
		#php artisan schedule:run --verbose --no-interaction 1>>sceduler.log #РАБОТАЕТ!!! пишет в файл а не в консоль
		php artisan schedule:run #1>>scedule.log
		wait
	done
