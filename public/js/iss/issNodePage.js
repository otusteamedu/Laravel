(function () {

    $(document).ready(function () {
        //отрисовка вкладок jQuery
        $('#tabPanels').tabs({
            'heighStyle': 'auto'
        });

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


    });

})();
