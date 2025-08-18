<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Калькулятор</title>
    </head>
    <body>
        <h1> Калькулятор</h1>
        <div class="resultnum"></div>
        <div class="calc">
            <input type="text" name="num"  class="result">
            <table>
                <tr>
                    <td class="number" data="0">
                        0
                    </td>
                    <td class="number" data="1">
                        1
                    </td>
                    <td class="number" data="2">
                        2
                    </td>
                </tr>
                <tr>
                    <td class="number" data="3">
                        3
                    </td>
                    <td class="number" data="4">
                        4
                    </td>
                    <td class="number" data="5">
                        5
                    </td>
                </tr>
                <tr>
                    <td class="number" data="6">
                        6
                    </td>
                    <td class="number" data="7">
                        7
                    </td>
                    <td class="number" data="8">
                        8
                    </td>
                </tr>

                <tr>
                    <td class="method" data=")">
                        )
                    </td>
                    <td class="method" data="(">
                        (
                    </td>
                    <td class="method" data=".">
                        .
                    </td>
                </tr>
                <tr>
                    <td class="number" data="9">
                        9
                    </td>
                    <td class="method" data="+">
                        +
                    </td>
                    <td class="method" data="-">
                        -
                    </td>
                    <tr>
                    <td class="method" data="/">
                        /
                    </td>
                    <td class="method" data="*">
                        *
                    </td>
                    <td class="eq">
                        <input type="button" class="return"  value="=" />
                    </td>
                </tr>
                </tr>
            </table>        
        </div>
        <div class="alternative">
        <p>
            Результат: {{ $calc }}
        </p>
        <form action="/calcpost" method="post">
            {{ csrf_field() }}
            <input type="text" name="num" placeholder="Первое число">
            <input type="text" name="num2" placeholder="Второе число">
            <ul>
                <li><label><input type="radio" name="method" value="multi"> Умножить</label></li>
                <li><label><input type="radio" name="method" value="plus"> Сложить</label></li>
                <li><label><input type="radio" name="method" value="minus"> Вычесть</label></li>
                <li><label><input type="radio" name="method" value="div"> Разделить</label></li>
            </ul>
            <input type="submit" value="=" />
        </form>
    </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).on('click','.number',function(){
            let result = $('.result').val();
            let num = $(this).attr('data');            
            result = result+num;
            $('.result').val(result);         
        });
        $(document).on('click','.method',function(){
            let result = $('.result').val();
            let method = $(this).attr('data');
            result = result+method; 
            $('.result').val(result);       
        });
        $(document).on('click','.return',function(){
            let result = $('.result').val();
            $.ajax({
                type: "POST",
                url: "/eval",
                data:{result:result},
                success: function(data){ 
                    $('.resultnum').html(data);   
                }        
            });      
        });    
    </script>
    <style>
        .number,.method{
            cursor: pointer;
            padding: 7px;
            border:1px solid #000;
            background:#ddd;
        }
    </style>
</html>