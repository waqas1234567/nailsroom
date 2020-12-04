@extends('layouts.admin_layout')
@section('content')


    <a href="Create-admin.html" class="btn custom-btn" >Dodaj administratora</a>

    <div class="starter-template">
        <table class="table table-width datatables">
            <thead>
            <tr>
                <th style="width: 21%;">Nazwa</th>
                <th>E-mail</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            @foreach($admins as $admin)
            <tr>
                <td>{{$admin->name}}</td>
                <td>{{$admin->email}}</td>
                <td style="text-align: right">
                    <a style="margin-right:1rem"  href="#"><img src="/public/assets/img/Vector.png"></a>
                    <a  href="#"><img src="/public/assets/img/delete.png"></a>


                </td>
            </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    @endsection
