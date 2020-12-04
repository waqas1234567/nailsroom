@extends('layouts.admin_layout')

@section('content')

    <style>
        label {
            color: #999;
            font-size: 18px;
            font-weight: normal;
             position: unset;
             pointer-events: all;
            left: 42%;
            top: 10px;
            transition: 0.3s ease all;
            -moz-transition: 0.3s ease all;
            -webkit-transition: 0.3s ease all;
        }
    </style>


    <a href="/add-marki" class="btn custom-btn" >Dodaj nową markę</a>

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
                <th style="width: 21%;">Nazwa marki</th>
                <th>Liczba kolekcji</th>
                <th>.</th>
            </tr>
            </thead>
            <tbody>
            @if(count($brands)>0)
                @foreach($brands as $b)
                    <tr>
                        <td>{{$b->name}}</td>
                        <td>{{$b->collections}}</td>
                        <td style="text-align: right">
                            <a data-toggle="tooltip" title="Kolekcji" style="margin-right:1rem"  href="/kolekcje/{{$b->id}}"><img src="/public/assets/img/gear.png"></a>
                            <a style="margin-right:1rem" data-toggle="tooltip" title="edytuj markę"  href="/edit-marki/{{$b->id}}"><img src="/public/assets/img/Vector.png"></a>
                            <a onclick="deleteMarki({{$b->id}})" data-toggle="tooltip" title="usuń markę"  href="#"><img src="/public/assets/img/delete.png"></a>
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
