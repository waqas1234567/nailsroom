@extends('layouts.admin_layout')
@section('content')
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

        .checkbox-style{
            position: relative;
            right: 3rem;
            bottom: 6px;
        }
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
        .position{
            position: relative;
            right: 0rem;
            bottom: 24px;
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
        <form method="post" action="/admin/dodaj-administratora">
            @csrf
            <button type="submit"  class="btn  custom-btn" >ZAPISZ</button>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-3">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Nazwa</h6>
                    </div>
                    <div class="col-md-6" >
                        <div class="group">
                            <input type="text" name="name" required>
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
                            <input type="email" name="email" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Hasło</h6>
                    </div>
                    <div class="col-md-6" >
                        <div class="group">
                            <input type="password" minlength="8" name="password" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Uprawnienia</h6>
                    </div>
                    <div class="col-md-12" >
                        @foreach($permissions as $p)

                            <?php $check=0 ?>

                            @foreach($adminPermissions as $a)

                                @if($a->adminpermission_id==$p->id)
                                       <?php $check=1;?>
                                    @endif

                                @endforeach

                            @if($check==1)

                            <div class="row mt-4">

                                <div class="col-md-2 checkbox-style" >
                                    <input type="checkbox" name="permission[]" value="{{$p->id}}" checked>
                                </div>
                                <div class="col-md-2 position" >
                                    <label>{{$p->name}}</label>

                                </div>

                            </div>
                                @else

                                    <div class="row mt-4">

                                        <div class="col-md-2 checkbox-style" >
                                            <input type="checkbox" name="permission[]" value="{{$p->id}}">
                                        </div>
                                        <div class="col-md-2 position" >
                                            <label>{{$p->name}}</label>

                                        </div>

                                    </div>

                                @endif

                        @endforeach








                    </div>
                </div>
            </div>









    </form>


    </div>

@endsection
