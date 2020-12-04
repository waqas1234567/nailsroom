@extends('layouts.admin_layout')

@section('content')




        <div class="starter-template" >

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
                @if ($message = Session::get('success'))
                    <div class="alert alert-success mt-4">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <p>{{ $message }}</p>

                    </div>
                @endif

            <div class="col-md-6 col-sm-12 ">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="border-bottom: none;">
                        <h6 class="heading-style" >Kolory :</h6>
                        <span class="span-style2"><input type='text' form="message" name="code" id="custom" /></span>

                    </li>


                </ul>

            </div>
            <div class="col-md-10 col-sm-8">
                <form id="message" action="/marki/dodaj-kolor" method="post" enctype="multipart/form-data" class="form-inline" style="position: relative;
    right: 2%;">
                    @csrf
                    <input type="hidden" name="collection_id" value="{{request()->segment(3)}}" id="collection-id">
                    <div class="form-group mx-sm-3">
                        <div class="group">
                            <input type="text" name="name" id="color-name" placeholder="Nazwa koloru" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>

                        </div>
                    </div>
                    <div class="form-group mx-sm-3">
                        <div class="group">
                            <img id='img-icon'  src="/public/assets/img/icon.png"></a>
                            <input id="my_file" name="icon" style="display:none" type="file">
                            <input id="color-icon" placeholder="Ikona koloru"  type="text"  disabled>
                            <span class="highlight"></span>
                            <span class="bar"></span>



                        </div>
                    </div>
                    <div class="mx-sm-3">
                        <button type="submit"  class="btn custom-btn-style mb-4">Dodaj</button>

                    </div>
                </form>
            </div>
            <div class="col-md-12 col-sm-12">

                <table class="table">
                    <thead>
                    <tr>
                        <th></th>

                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($colors)>0)
                    @foreach($colors as $b)
                    <tr>
                        <td><span style=" height: 25px;
  width: 25px;
  background-color: {{$b->code}};
  border-radius: 50%;
  display: inline-block;"></span><span class="table-span">{{$b->name}}</span></td>

                        <td style="text-align: right">
                            <a  href="/marki/delete-color/{{$b->id}}"><img src="/public/assets/img/delete.png"></a>


                        </td>
                    </tr>
                    @endforeach
                        @else
                     <p>
                         Brak koloru do pokazania !</p>
                    @endif

                    </tbody>
                </table>
            </div>





        </div>


    @endsection
