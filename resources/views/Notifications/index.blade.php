@extends('layouts.admin_layout')


@section('content')
    <a href="/powiadomienia/dodaj-powiadomienia" class="btn custom-btn" >Dodaj nowe</a>

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
                <th style="width: 21%;">Tytuł</th>
                <th>Data dodania</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($notifications as $n)
            <tr>
                <td>{{$n->title}}</td>
                <td>{{$newDate = date("d-m-Y", strtotime($n->created_at))}}</td>
                <td style="text-align: right">
                    <a style="margin-right:1rem"  href="/Powiadomienia/edytowac-powiadomienia/{{$n->id}}"><img src="/public/assets/img/Vector.png"></a>
                    <a onclick="deleteNotification({{$n->id}})"   href="#"><img src="/public/assets/img/delete.png"></a>
                </td>
            </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
