@extends('layouts.admin_layout')

@section('content')


    <a href="/marki/dodaj-kolekcje/{{request()->segment(3)}}" class="btn custom-btn" >Dodaj kolekcje</a>

    <div class="starter-template">

        @if ($message = Session::get('success'))
            <div class="alert alert-success mt-4">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p>{{ $message }}</p>

            </div>
        @endif
        <table class="table table-width datatables">
            <thead>
            <tr>
                <th style="width: 21%;">kolekcja</th>
                <th>.</th>
            </tr>
            </thead>
            <tbody>
            @if(count($collections)>0)
                @foreach($collections as $b)
                    <tr>
                        <td>{{$b->name}}</td>

                        <td style="text-align: right">
                            <a data-toggle="tooltip" title="zabarwienie" style="margin-right:1rem"  href="/marki/kolor/{{$b->id}}"><img src="/public/assets/img/color.png"></a>
                            <a style="margin-right:1rem" data-toggle="tooltip" title="edytuj kolekcje"  href="/marki/edytuj-kolekcje/{{$b->id}}"><img src="/public/assets/img/Vector.png"></a>
                            <a onclick="deleteCollection({{$b->id}},{{$b->brand_id}})" data-toggle="tooltip" title="usuń kolekcje"  href="#"><img src="/public/assets/img/delete.png"></a>
                        </td>
                    </tr>



                @endforeach
            @else
                <tr>
                    <td></td>
                    <td></td>
                    <td>No data to show!</td>
                    <td>

                    </td>
                </tr>


            @endif
            </tbody>
        </table>
    </div>

@endsection
