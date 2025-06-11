//---------------- работа с картой в доставке ---------------------
var myMap;
var point;

$(document).ready(function() {
    const $map = $('#map');
    if ($map.length > 0) {
        ymaps.ready(geoInit);
    } 
});

function geoInit() {
    const mapWidth = document.getElementById('map').offsetWidth;
    document.getElementById('map').style.height = mapWidth * 0.8 + 'px';

    myMap = new ymaps.Map('map', {
        center: [58.00, 56.22],
        zoom: 11
    });

    const props = {
        fillColor: 'rgba(0, 0, 0, 0.1)',
        strokeColor: '#0000FF',
        strokeWidth: 2,
        strokeStyle: 'solid'
    };
    var myGeoObject1 = new ymaps.GeoObject(
        {
            geometry: {
                type: "Polygon",
                coordinates: [
                    [
                        [58.004481, 56.181631],
                        [58.020273, 56.252322],
                        [57.999265, 56.270446],
                        [57.990337, 56.233553],
                        [58.004481, 56.181631],
                    ],
                ],
            },
        },
        props
    );
    var myGeoObject2 = new ymaps.GeoObject(
        {
            geometry: {
                type: "Polygon",
                coordinates: [
                    [
                        [58.006341, 56.124592],
                        [58.035899, 56.307232],
                        [57.996768, 56.335864],
                        [57.967807, 56.149503],
                        [58.006341, 56.124592],
                    ],
                ],
            },
        },
        props
    );
    var myGeoObject3 = new ymaps.GeoObject(
        {
            geometry: {
                type: "Polygon",
                coordinates: [
                    [
                        [58.006022, 55.911359],
                        [58.113861, 56.297623],
                        [58.113861, 56.407623],
                        [58.009988, 56.367834],
                        [57.960000, 56.307834],
                        [57.933245, 56.164821],
                        [58.006022, 55.911359],
                    ]
                ],
            },
        },
        props
    );

    myMap.geoObjects.add(myGeoObject1);
    myMap.geoObjects.add(myGeoObject2);
    myMap.geoObjects.add(myGeoObject3);

    myMap.balloon.open([58.0000, 56.2300], 'Доставка - ближняя зона 200 руб., средняя зона 300 руб., дальняя зона 500 руб.');

    const lon = $('[data-lon]').attr('data-lon');
    const lat = $('[data-lat]').attr('data-lat');
    if (lon && lat) {
        point = new ymaps.Placemark(
            [lat, lon], 
            { balloonContent: 'Адрес доставки' },
            { preset: 'islands#icon', iconColor: '#0095b6'}
        );
        myMap.geoObjects.add(point);
    }
}


//---------------- доставка ----------------------------------
const $address = $('#address');

$(document).on('change', 'input[name=delivery]', function() {
    if ($(this).prop('checked') && $(this).val() == 'curier') {
        $address.prop('disabled', false);
    } else {
        $address.prop('disabled', true);
        $address.val('');

        const _token = $('meta[name="csrf-token"]').attr('content');
        const data = { _token };
        $.ajax({
            url: '/cart/pickup',
            method: 'POST',
            data: data,
            success: function(response) {
                myMap.geoObjects.remove(point);
            },
            error: function(error) {
                console.error(error);
            }
        });
    }
});


$address.suggestions({
    token: 'ff0900b6ba8078858b621fb551882f475d68f86c',
    type: "ADDRESS",
    onSelect: function(suggestion) {
        const lat = suggestion.data.geo_lat;
        const lon = suggestion.data.geo_lon;
        const address = $address.val();
        const _token = $('meta[name="csrf-token"]').attr('content');
        const data = { lat, lon, address, _token };
        
        $.ajax({
            url: '/cart/delivery',
            method: 'POST',
            data: data,
            success: function(response) {
                point = new ymaps.Placemark(
                    [lat, lon], 
                    { balloonContent: 'Адрес доставки' },
                    { preset: 'islands#icon', iconColor: '#0095b6'}
                );
                myMap.geoObjects.add(point);
            },
            error: function(error) {
                console.error(error);
            }
        });
    }
});