@extends('layouts.admin_layout')


@section('content')


    <a href="/add-newsy" class="btn custom-btn" >Dodaj nowy</a>

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
                <th>Kategoria</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @if(count($news)>0)
            @foreach($news as $n)

            <tr>
                <td>{{$n->title}}</td>
                <td>{{ date('d-m-Y', strtotime($n->created_at))}}</td>
                <td>{{$n->newscategory->name}}</td>
                <td style="text-align: right">
                    <a style="margin-right:1rem"  href="/editNewsy/{{$n->id}}"><img src="/public/assets/img/Vector.png"></a>
                    <a  onclick="deleteNews({{$n->id}})"  href="#"><img src="/public/assets/img/delete.png"></a>



                </td>
            </tr>
                @endforeach
                @else
                <tr>
                    <td></td>
                    <td></td>
                    <td>Brak rekordu na wystawie!</td>
                    <td>

                    </td>
                </tr>


                @endif

            </tbody>
        </table>
    </div>


@endsection
