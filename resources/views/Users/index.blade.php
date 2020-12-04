@extends('layouts.admin_layout')

@section('content')
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
                <th style="width: 21%;">Użytkownik</th>
                <th></th>


            </tr>
            </thead>
            <tbody>
            @foreach($users as $u)
            <tr>
                <td>{{$u->email}}</td>

                <td style="text-align: right">
                    <a onclick="deleteUser({{$u->id}})" ><img src="/public/assets/img/delete.png"></a>


                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endsection
