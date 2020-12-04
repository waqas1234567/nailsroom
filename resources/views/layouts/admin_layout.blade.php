<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Nailsroom</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/starter-template/">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <!-- Bootstrap core CSS -->
    <link href="http://panel.nailsroom.com.pl/assets/dist/css/bootstrap.css" rel="stylesheet">
    <link href="http://panel.nailsroom.com.pl/css/style.css" rel="stylesheet">
    <link href="http://panel.nailsroom.com.pl/assets/spectrum/spectrum.css" rel="stylesheet">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }


    </style>
    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">
</head>
<body>
<nav  class="navbar navbar-expand-md navbar-dark bg-dark fixed-top nav-margin">
    <img class="logo-image"  src="/public/assets/img/logo.png" alt="" width="72" height="72">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault" style="height: 109px !important;">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item  {{ (request()->segment(1) == 'marki' || request()->segment(1) == '' || request()->segment(1) == 'add-marki'  || request()->segment(1) == 'edit-marki' ) ? 'active' : '' }}">
                <a class="nav-link" href="/marki">Marki</a>
            </li>
            {{--            <li class="nav-item">--}}
            {{--                <a class="nav-link" href="Sklepy.html">Sklepy </a>--}}
            {{--            </li>--}}
            {{--            <li class="nav-item">--}}
            {{--                <a class="nav-link" href="Uzytkownicy.html">Uzytkownicy </a>--}}
            </li>
            <li class="nav-item {{(request()->segment(1) == 'sklepy')? 'active' : '' }}">
                <a class="nav-link" href="/sklepy">Sklepy</a>
            </li>
            <li class="nav-item {{ (request()->segment(1) == 'newsy' || request()->segment(1) == 'add-newsy' || request()->segment(1) == 'editNewsy' ) ? 'active' : '' }}">
                <a class="nav-link" href="/newsy">Newsy/Aktualności</a>
            </li>


            <li class="nav-item {{(request()->segment(1) == 'uzytkownicy')? 'active' : '' }}">
                <a class="nav-link" href="/uzytkownicy">Użytkownicy </a>
            </li>

            <li class="nav-item {{(request()->segment(1) == 'powiadomienia')? 'active' : '' }}">
                <a class="nav-link" href="/powiadomienia">Powiadomienia </a>
            </li>

            <li class="nav-item {{(request()->segment(1) == 'admin')? 'active' : '' }}">
                <a class="nav-link" href="/admin">Admin </a>
            </li>


        </ul>
        <a href="{{ url('/logout') }}" id="logout-button" >WYLOGUJ</a>

    </div>
</nav>



<main role="main" class="container">

    @yield('content')

</main><!-- /.container -->

<script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="/assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="http://panel.nailsroom.com.pl/assets/spectrum/spectrum.js"></script>
<script src="http://panel.nailsroom.com.pl/custom.js"></script>

<script>
    $(document).ready( function () {
        $('.datatables').DataTable({
            "paging":   true,
            "searching": false,
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": true,
            "bAutoWidth": false,
            "order": [[ 1, "desc" ]]

        });


    } );


    function deleteNews(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                window.location.href ="/delete-news/"+id;
            }
        })

    }
    function deleteMarki(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                window.location.href ="/marki/delete-marki/"+id;
            }
        })
    }
    function deleteCollection(id,brand_id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                window.location.href ="/marki/delete-collection/"+id+'/'+brand_id;
            }
        })
    }
    function deleteUser(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                window.location.href ="/Uzytkownicy/delete-user/"+id;
            }
        })
    }
    function deleteNotification(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                window.location.href ="/Powiadomienia/delete-notification/"+id;
            }
        })
    }


    function deleteAdmin(id) {


        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                window.location.href ="/admin/delete-admin/"+id;
            }
        })
    }



</script>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $("#custom").spectrum({
            color: "#f00",
            preferredFormat: "hex",
            showInput: true,
            showPalette: true,
        });
        document.getElementById('img-icon').onclick = function() {
            document.getElementById('my_file').click();
        };

        $('input[type=file]').change(function (e) {
            $('#color-icon').val($(this).val());
        });
    });


</script>
<script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzf7KnzVx3iLASRh25OP_bYgTpUD-dIW8&libraries=places"></script>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $("#custom").spectrum({
            color: "#f00",
            preferredFormat: "hex",
            showInput: true,
            showPalette: true,
        });
        document.getElementById('img-icon').onclick = function() {
            document.getElementById('my_file').click();
        };

        $('input[type=file]').change(function (e) {
            $('#color-icon').val($(this).val());
        });
    });


    function getCollections(id){
        $('#collections-list').html('<img src="/public/img/loader.gif">');


        jQuery.ajax({
            url: '/getCollections',
            method: 'post',
            data:{"id":id,"_token": "{{ csrf_token() }}"},

            success: function(result){


                $('#brand_id').val(result.brand_id);
                $('#collections-list').html(result.html);


            }});

    }
    function getCollectionsedit(id,store_id){
        $('#collections-list').html('<img src="/public/img/loader.gif">');


        jQuery.ajax({
            url: '/getCollectionsedit',
            method: 'post',
            data:{"id":id,'store_id':store_id,"_token": "{{ csrf_token() }}"},

            success: function(result){


                $('#brand_id').val(result.brand_id);
                $('#collections-list').html(result.html);


            }});

    }

    function saveCollection() {
        $('#collection-save-button').prop('disabled',true)
        var deletedValue=[];
        $("input[name='collections[]']:checkbox:not(:checked)").each(function () {
            deletedValue.push($(this).val());
        });
        jQuery.ajax({
            url: '/saveCollections',
            method: 'post',
            data:{"data":$('#collection-form').serializeArray(),'deletedValue':deletedValue,"_token": "{{ csrf_token() }}"},

            success: function(result){
                $('#collection-save-button').prop('disabled',false)
                $('#exampleModal').modal('hide');


            }});
    }


    function initialize() {

        var mapOptions, map, marker, searchBox
        infoWindow = '',
            addressEl = document.querySelector( '#map-search' ),
            latEl = document.querySelector( '.latitude' ),
            longEl = document.querySelector( '.longitude' ),
            element = document.getElementById( 'map-canvas' );
        // city = document.querySelector( '.reg-input-city' );
        // var lat=document.getElementById("lat").value;
        // var long=document.getElementById("long").value;


        var  latitude=51.9194;
        var longitude=19.1451;


        mapOptions = {
            // How far the maps zooms in.
            zoom: 13,
            // Current Lat and Long position of the pin/
            center: new google.maps.LatLng(latitude,longitude),
            // center : {
            // 	lat: -34.397,
            // 	lng: 150.644
            // },
            disableDefaultUI: false, // Disables the controls like zoom control on the map if set to true
            scrollWheel: true, // If set to false disables the scrolling on the map.
            draggable: true, // If set to false , you cannot move the map around.
            // mapTypeId: google.maps.MapTypeId.HYBRID, // If set to HYBRID its between sat and ROADMAP, Can be set to SATELLITE as well.
            // maxZoom: 11, // Wont allow you to zoom more than this
            // minZoom: 9  // Wont allow you to go more up.

        };

        /**
         * Creates the map using google function google.maps.Map() by passing the id of canvas and
         * mapOptions object that we just created above as its parameters.
         *
         */
        // Create an object map with the constructor function Map()
        map = new google.maps.Map( element, mapOptions ); // Till this like of code it loads up the map.

        /**
         * Creates the marker on the map
         *
         */
        marker = new google.maps.Marker({
            position: mapOptions.center,
            map: map,
            // icon: 'http://pngimages.net/sites/default/files/google-maps-png-image-70164.png',
            draggable: true
        });

        /**
         * Creates a search box
         */
        searchBox = new google.maps.places.SearchBox( addressEl );


        /**
         * When the place is changed on search box, it takes the marker to the searched location.
         */
        google.maps.event.addListener( searchBox, 'places_changed', function () {
            var places = searchBox.getPlaces(),
                bounds = new google.maps.LatLngBounds(),
                i, place, latitude, longitude, resultArray,
                addresss = places[0].formatted_address;
            console.log(places);
            console.log(addresss);

            for( i = 0; place = places[i]; i++ ) {
                bounds.extend( place.geometry.location );
                marker.setPosition( place.geometry.location );  // Set marker position new.
            }

            map.fitBounds( bounds );  // Fit to the bound
            map.setZoom( 15 ); // This function sets the zoom to 15, meaning zooms to level 15.
            // console.log( map.getZoom() );

            lat = marker.getPosition().lat();
            long = marker.getPosition().lng();
            $('#latitude_field').val(lat);
            $('#longitude_field').val(long);
            latEl.value = latitude;
            longEl.value = longitude;

            resultArray =  places[0].address_components;

            // Get the city and set the city input value to the one selected
            // for( var i = 0; i < resultArray.length; i++ ) {
            //     if ( resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0] ) {
            //         citi = resultArray[ i ].long_name;
            //         city.value = citi;
            //     }
            // }

            // Closes the previous info window if it already exists
            if ( infoWindow ) {
                infoWindow.close();
            }
            /**
             * Creates the info Window at the top of the marker
             */
            infoWindow = new google.maps.InfoWindow({
                content: addresss
            });

            infoWindow.open( map, marker );
        } );


        /**
         * Finds the new position of the marker when the marker is dragged.
         */
        google.maps.event.addListener( marker, "dragend", function ( event ) {
            var lat, long, address, resultArray, citi;

            console.log( 'i am dragged' );
            lat = marker.getPosition().lat();
            long = marker.getPosition().lng();
            $('#latitude_field').val(lat);
            $('#longitude_field').val(long);
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode( { latLng: marker.getPosition() }, function ( result, status ) {
                if ( 'OK' === status ) {  // This line can also be written like if ( status == google.maps.GeocoderStatus.OK ) {
                    address = result[0].formatted_address;
                    resultArray =  result[0].address_components;

                    // Get the city and set the city input value to the one selected
                    // for( var i = 0; i < resultArray.length; i++ ) {
                    //     if ( resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0] ) {
                    //         citi = resultArray[ i ].long_name;
                    //         console.log( citi );
                    //         city.value = citi;
                    //     }
                    // }
                    addressEl.value = address;
                    latEl.value = lat;
                    longEl.value = long;

                } else {
                    console.log( 'Geocode was not successful for the following reason: ' + status );
                }

                // Closes the previous info window if it already exists
                if ( infoWindow ) {
                    infoWindow.close();
                }

                /**
                 * Creates the info Window at the top of the marker
                 */
                infoWindow = new google.maps.InfoWindow({
                    content: address
                });

                infoWindow.open( map, marker );
            } );
        });


    }

    $( document ).ready(function() {
        initialize();
    });


    function enableButton(id) {
        var isChecked = $("#brandCheckbox" + id).is(":checked");
        if (isChecked) {
            $('#storeButton' + id).prop('disabled', false)
        } else {
            $('#storeButton' + id).prop('disabled', true)
        }
    }
    </script>


</body>
</html>

