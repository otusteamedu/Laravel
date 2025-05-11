(function () {

    //Блок "Маршруты обучения"
    //Блок "Маршруты обучения": всплывающие сообщения на
    $('.cover').mouseenter(function(evt) {
        evt.preventDefault();
        $(this).children('div').removeClass('hide').addClass('show');
    });
    $('.cover').mouseleave(function(evt) {
        evt.preventDefault();
        $(this).children('div').removeClass('show').addClass('hide');
    });



    //Блок "Диаграммы для менеджера и администратора"
    //Блок "Диаграммы для менеджера и администратора": получение входных данных
    $('.graphic').each(function () {
        //console.log($(this).find('canvas').attr('id'));
        //console.log($(this).find('.initialData').find('.json').text());
        //console.log($(this).find('.initialData').find('.diagramName').text());

        //инициализация переменных
        let diagramColors = ['red', 'blue', 'green', 'yellow', 'gray'];
        let labels = [];
        let routes = [];
        let routesData = {};
        let datasetsArr = [];
        let empIndex = 0;

        //получение данных
        let initialData = JSON.parse($(this).find('.initialData').find('.json').text());

        for (let emp in initialData) { //перебор сотрудников
            //сотрудники (ось х диаграммы)
            labels.push(emp);

            //данные сотрудников (ось y диаграммы)
            for(i = 0; i < initialData[emp].length; i++) { //перебор маршрутов сотрудника
                //маршруты
                for (let tmpRouteName in initialData[emp][i]) {
                    //если такой маршрут еще не учтен, добавляем его в массив и создаем под него свойство в объекте данных
                    if ($.inArray(tmpRouteName, routes) === -1) {
                        routes.push(tmpRouteName);
                        routesData[tmpRouteName] = [];
                    }
                    // для каждого маршрута добавляем его % прохождения для данного сотрудника
                    routesData[tmpRouteName][empIndex] = initialData[emp][i][tmpRouteName];
                }
            }
            empIndex++;
        }
        //console.log(routesData);
        //console.log(labels);

        //загрузка данных в диаграмму
        let colorIterator = 0;
        let j = 0;
        for (let currentRouteName in routesData) {
            let color = colorIterator % (diagramColors.length-1);

            datasetsArr[j] = {
                label: currentRouteName,
                data: routesData[currentRouteName],
                borderColor: diagramColors[color],
                backgroundColor: diagramColors[color],
                hoverBackgroundColor: 'rgba(255, 99, 132, 0.8)',
                hoverBorderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                borderRadius: 10,
            };

            colorIterator++;
            j++;
        }

        let data = {
            labels: labels,
            datasets: datasetsArr
        };

        //настройка диаграммы
        let config = {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Education routes chart'
                    }
                }
            },
        };

        // Get the canvas element
        //console.log($(this).find('div canvas').attr('id'));
        let ctx = document.getElementById($(this).find('div canvas').attr('id'));

        // Create the styled bar chart
        let myStyledBarChart = new Chart(ctx, config);
    });
})();
