@extends('layouts.admin_layout')


@section('content')
    <style>
        .list-group-item{
            padding: 0px;
        }
        .group{
            position: relative;
            margin-bottom: 6px;
        }
        .list-table{
            position: absolute !important;
            right: 78% !important;
            top: 1rem !important;
        }
        .table-span {
            position: relative;
            left: -20% !important;
            top: 0px;
        }
        .list-group-item{
            border-bottom: none;
        }

        #img-icon {
            position: absolute;
            top: -1rem;
            right: 33rem;

        }
        .span-style2 {
            color: #7A7A7A;
            font-size: 15px;
            position: relative;
            right: 46%;
            bottom: -23px;
        }

    </style>




    <div class="starter-template">
        @if (count($errors) > 0)
            <div class="alert alert-danger alert-dismissible fade show mt-4">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/marki/dodaj-wzór" method="post" enctype="multipart/form-data">
            @csrf
            <button style="    position: relative;
    bottom: 3rem;" type="submit" class="btn  custom-btn" >ZAPISZ</button>
            <div class="col-md-8">

                <div class="row">
                    <div class="col-md-2">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Zdjęcie</h6>
                    </div>
                    <div class="col-md-10">
                        <div class="group">
                            <input id="my_file" onchange="readURL(this)" name="image" style="display:none" type="file" >
                            {{--                            <input id="color-icon" type="text" disabled="">--}}
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                        <button type="button" class="btn  custom-btn-2" id="img-icon">Wybierz plik</button>
                        <img  id="blah" style="display: none; position: relative;   bottom: 1rem;
    right: 6rem;" src="#" alt="Notification image" />

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">

                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">kolor tła</h6>
                    </div>
                    <div class="col-md-13 mt-4">
                        <div id="cptest" class="input-group" title="Using color option">
                            <input id="foreground-1st-color" name="background"  type="text" class="form-control "/>
                            <span class="input-group-append">
    <span class="input-group-text colorpicker-input-addon"><i></i></span>
  </span>
                        </div>

                    </div>
                    <input type="hidden" name="brand_id" value="{{request()->segment(3)}}">
                </div>
            </div>
        </form>


    </div>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {

                $('#blah').show();
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(100)

                };

                reader.readAsDataURL(input.files[0]);
            }
        }



    </script>
@endsection
