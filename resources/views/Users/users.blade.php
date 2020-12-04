@extends('layouts.admin_layout')

@section('content')
    <div class="starter-template">
        <table class="table table-width">
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
                    <a  href="#"><img src="/public/assets/img/delete.png"></a>


                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endsection
