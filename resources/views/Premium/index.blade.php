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
        input:focus ~ label, input:valid ~ label {
            top: 5px;
            font-size: 18px;
            color: #7A7A7A;
            left: 6%;
        }
    </style>
    <form  action="/dodaj-sklepy" id="formid" method="post" >
        @csrf
        <button type="submit" class="btn  custom-btn" >ZAPISZ</button>

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

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Nazwa sklepu</h6>
                    </div>
                    <div class="col-md-6 " >
                        <div class="group">
                            <input type="text" name="name" placeholder="" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Ulica</h6>
                    </div>
                    <div class="col-md-6 " >
                        <div class="group">
                            <input type="text" name="street" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Numer lokalu</h6>
                    </div>
                    <div class="col-md-6 " >
                        <div class="group">
                            <input type="text" name="apartment_no" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Miejscowość</h6>
                    </div>
                    <div class="col-md-6 " >
                        <div class="group">
                            <input id="map-search" placeholder="" type="text" name="place" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">

                    </div>

                    <div class="col-md-6 " >
                        <div style='height: 15rem;
    position: relative;
    overflow: hidden;

    width: 100%;' id="map-canvas"></div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Szerokość geograficzna</h6>
                    </div>
                    <div class="col-md-6 " >
                        <div class="group">
                            <input type="text" name="latitude"  id="latitude_field"  required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Długość geograficzna</h6>
                    </div>
                    <div class="col-md-6 " >
                        <div class="group">
                            <input type="text" name="longitude" id="longitude_field" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Telefon</h6>
                    </div>
                    <div class="col-md-6 " >
                        <div class="group">
                            <input type="text" name="phone" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">e-mail</h6>
                    </div>
                    <div class="col-md-6 " >
                        <div class="group">
                            <input type="email" name="email" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">opis</h6>
                    </div>
                    <div class="col-md-12 " >
                        <div class="group">
                            <textarea cols="10" rows="10" name="des" ></textarea>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Strona www</h6>
                    </div>
                    <div class="col-md-6 " >
                        <div class="group">
                            <input type="url" name="web_page" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>

                        </div>
                    </div>
                </div>

            </div>





            <hr>
            <div class="col-md-6 col-sm-12 ">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="border-bottom: none;">
                        <h6 class="heading-style" >Marki/Kolory</h6>

                    </li>


                </ul>


            </div>

        </div>
    </form>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: 32px">
                <div class="modal-header">
                    <h5 style="    color: #7A7A7A;
    position: relative;
    left: 42%" class="modal-title" id="exampleModalLabel">Kolekcje</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form  id="collection-form">


                        {{--                        <input type="hidden" id="brand_id" name="brandId" >--}}
                        <div  id="collections-list">

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a  style="position: relative;
    right: 32%;padding-left: 6%;
    padding-right: 6%;color:white"   onclick="saveCollection()"  id="collection-save-button" class="btn  custom-btn" >ZAPISZ</a>
                </div>
            </div>
        </div>
    </div>

@endsection
