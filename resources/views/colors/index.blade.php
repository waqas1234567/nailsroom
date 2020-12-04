@extends('layouts.admin_layout')

@section('content')
<main role="main" class="container">

    <a href="/marki/dodaj-kolor/{{request()->segment(3)}}" class="btn custom-btn" >Dodaj nową kolor</a>

    <div class="starter-template">
        <table class="table table-width datatables">
            <thead>
            <tr>
                <th style="width: 21%;">kolor</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($colors as $b)
            <tr>
                <td>{{$b->name}}</td>
            </tr>
            @endforeach


            </tbody>
        </table>
    </div>

</main>
@endsection
