@extends('layouts.admin_layout')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success mt-4">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p>{{ $message }}</p>

        </div>
    @endif
    

    <a href="createStore.html" class="btn custom-btn" >Dodaj nowy sklep</a>

    <div class="starter-template">
        <table class="table table-width datatables">
            <thead>
            <tr>
                <th style="width: 21%;">Nazwa sklepu</th>
                <th>Marki</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($stores as $store)
                <tr>
                    <td>{{$store->name}}</td>
                    <td>

                        <?php  $brand_array=array()  ?>
                        @foreach($store->brand as $b)
                            <?php
                            array_push($brand_array,$b->name)
                            ?>
                        @endforeach

                        {{implode(' / ', $brand_array)}}

                    </td>
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
