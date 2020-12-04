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

    <a href="createStore.html" class="btn  custom-btn" >ZAPISZ</a>

    <div class="starter-template">

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-3">
                    <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Nazwa</h6>
                </div>
                <div class="col-md-6" >
                    <div class="group">
                        <input type="text" required>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">E-mail</h6>
                </div>
                <div class="col-md-6" >
                    <div class="group">
                        <input type="text" required>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <ul class="list-group">


                            <li class="list-group-item d-flex justify-content-between align-items-center mb-1">
                                <span><input  type="checkbox"   value="" class="gridCheck1"><label>test</label></span>
                                {{--                            <span class="table-span">{{$b->name}}</span>--}}
                                <span>

                </span>

                            </li>



                    </ul>
                </div>
                <div class="col-md-6">
                </div>
            </div>
        </div>

    </div>





    </div>


    </form>


    </div>


@endsection
