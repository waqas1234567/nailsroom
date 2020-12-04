@extends('layouts.admin_layout')

@section('content')


    <a href="/add-marki" class="btn custom-btn" >Dodaj nową markę</a>

    <div class="starter-template">
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
                    <a style="margin-right:1rem"  href="#"><img src="assets/img/Vector.png"></a>
                    <a  href="#"><img src="assets/img/delete.png"></a>
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
