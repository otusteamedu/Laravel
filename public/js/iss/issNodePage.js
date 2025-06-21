(function (
    inputDownloadAddress,
    inputOpenAddress,
    inputCheckExamAddress
) {

    //задаем переменные модуля
    var downloadAddress = inputDownloadAddress;
    var openAddress = inputOpenAddress;
    var checkExamAddress = inputCheckExamAddress;

    $(document).ready(function () {
        //отрисовка вкладок jQuery
        $('#tabPanels').tabs({
            'heighStyle': 'auto'
        });


        //СЕЛЕКТОРЫ НА СТРАНИЦЕ
        //селектор для отображение элементов заданного типа для файлов инструкций (текст или пдф)
        $('#instructionTypeSelector').change(function () {
            let tmp = $(this).val();
            let initial = $('#instructionSelector option[initial]').val();
            $('#instructionSelector').val(initial);
            $('#instructionSelector option').each(function () {
                if ($(this).attr('materialType') === tmp)
                     $(this).removeAttr('hidden');
                else $(this).attr('hidden', true);
            });
        });

        //селектор для отображение элементов заданного типа для файлов инструкций (видео файлы)
        $('#instructionTypeSelectorVideo').change(function () {
            let tmp = $(this).val();
            let initial = $('#videoSelector option[initial]').val();
            $('#videoSelector').val(initial);
            $('#videoSelector option').each(function () {
                if ($(this).attr('materialType') === tmp)
                    $(this).removeAttr('hidden');
                else $(this).attr('hidden', true);
            });
        });


        //ЗАПРОСЫ НА АЯКС КОНТРОЛЛЕРЫ
        //включение веб токена во все запросы страницы
        $.ajaxSetup({
            headers:
                { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        //загрузки
        //загрузка-сохранение файла текстовой инструкции на ПК клиента
        $('#loadInstruction').click(function () {
            let targetFileType = $('#instructionTypeSelector').val();
            let targetFileName = $('#instructionSelector').val();
            if (targetFileName === "") {
                //console.log('initial!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
                targetFileName = null;
            }

            //console.log('________________');
            //console.log('TRY DOWNLOAD FILE');
            //console.log('type:' + targetFileType);
            //console.log('name:' + targetFileName);
            //console.log($('meta[name="csrf-token"]').attr('content'));
            //console.log(downloadAddress+'/'+targetFileType+'/'+targetFileName);

            window.open(downloadAddress+'/'+targetFileType+'/'+targetFileName, '_blank');
        });

        //загрузка-сохранение файла видео инструкции на ПК клиента
        $('#loadVideo').click(function () {
            let targetFileType = $('#instructionTypeSelectorVideo').val();
            let targetFileName = $('#videoSelector').val();
            if (targetFileName === "") {
                targetFileName = null;
            }
            window.open(downloadAddress+'/'+targetFileType+'/'+targetFileName, '_blank');
        });

        //отображения
        //отобразить файл в новой вкладке
        $('#viewInstruction').click(function () {
            let targetFileType = $('#instructionTypeSelector').val();
            let targetFileName = $('#instructionSelector').val();
            if (targetFileName === "") {
                targetFileName = null;
            }
            window.open(openAddress+'/'+targetFileType+'/'+targetFileName, '_blank');
        });

        $('#viewVideo').click(function () {
            let targetFileType = $('#instructionTypeSelectorVideo').val();
            let targetFileName = $('#videoSelector').val();
            if (targetFileName === "") {
                targetFileName = null;
            }
            window.open(openAddress+'/'+targetFileType+'/'+targetFileName, '_blank');
        });


        //проверка экзамена
        $('#submitExam').click(function () {
            //console.log()
            let apiData = $('#checkExamForm').serialize();

            function callbackF(dataReturned)
            {
                console.log('request='+dataReturned);
                let grey = 'background-color: rgba(200,200,200,0.8)';
                let green =  'background-color: rgba(100,200,100,0.8)';
                let red = 'background-color: rgba(200,100,100,0.8)';
                let color = grey;
                let message = '';

                let errors = JSON.parse(dataReturned).error;
                let success = JSON.parse(dataReturned).success;

                //проверка есть ли ошибки
                if (errors !== undefined) {
                    message = errors;
                } else {
                    message = success;
                }

                if (message === 'Exam failed' || message === 'Экзамен не сдан') {
                    color = red;
                }
                if (message === 'Exam passed' || message === 'Экзамен сдан') {
                    color = green;
                }
                if (message === 'Exam sent to teacher' || message === 'Экзамен отправлен на проверку') {
                    color = green;
                }

                $('#issMessage').text(message).attr('hidden', false).css('background-color', color);
                setTimeout(
                    function (tmpColor = grey) {
                        $('#issMessage').text('').attr('hidden', true).css('background-color', tmpColor);
                        },
                    2000
                );
            }
            $.post(checkExamAddress, apiData)
                .done(callbackF)
                .fail(function (xhr, status, error) { console.log('ERROR: ' + xhr + ' ' + status + ' ' + error); });
        });
    });

})(
    'http://localhost/iss/download',
    'http://localhost/iss/open',
    'http://localhost/iss/checkExam'
);
