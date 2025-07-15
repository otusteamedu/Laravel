@extends('layouts.mainViewTemplate')

@section('content')
    <h2>Test API</h2>

    <form>
        <input type="text" placeholder="enter issUserId" />
        <input  type="button" id="test_api_button" value="testapi" />
    </form>
@endsection

@push('mainScripts')
    <script>
        //для кнопки проверки GET аякс на апи ларвел
            function go_api(){
                console.log('test gooooo!');
                let apiObjectMy = new XMLHttpRequest();

                apiObjectMy.onload = function(){
                if(this.status === 200){
                    console.log(apiObjectMy.response);
                } else { console.log('error request'); }
                };

                apiObjectMy.open('GET', 'http://127.0.0.1:80/api/tmp3', true);
                //apiObjectMy.setRequestHeader('Accept','application/json'); //это для ларавел, а так можно задать еще apiObjectMy.responseType = 'json'; НО тогда вернет не строку а ОБЪЕКТ json
                apiObjectMy.responseType = 'json';
                apiObjectMy.send();
            }

            var myButton = document.querySelector('#test_api_button');
            myButton.addEventListener('click',go_api);
    </script>
@endpush
