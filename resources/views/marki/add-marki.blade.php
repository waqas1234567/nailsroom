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
    </style>
    <form  action="/marki/add-marki" method="post" >
        @csrf

        <button type="submit"  class="btn  custom-btn" >ZAPISZ</button>

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
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-2">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Marki</h6>
                    </div>
                    <div class="col-md-10 " id="nazwa-field">
                        <div class="group">
                            <input type="text" name="brand" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Zdjęcie</h6>
                    </div>
                    <div class="col-md-10">
                        <div class="group">
                            <input id="my_file" name="image" style="display:none" type="file" >
                            {{--                            <input id="color-icon" type="text" disabled="">--}}
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                        <button type="button" class="btn  custom-btn-2" id="img-icon">Wybierz plik</button>

                    </div>
                </div>

            </div>
        </div>

    </form>
    </div>

    </div>

@endsection
