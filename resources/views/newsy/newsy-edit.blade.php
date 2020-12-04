@extends('layouts.admin_layout')
  @section('content')

      <style>
          .list-group-item{
              padding: 0px;
          }
          .group{
              position: relative;
              margin-bottom: 6px;
          }
          .list-table{
              position: absolute !important;
              right: 78% !important;
              top: 1rem !important;
          }
          .table-span {
              position: relative;
              left: -20% !important;
              top: 0px;
          }
          .list-group-item{
              border-bottom: none;
          }
      </style>
      <form  action="/update-newsy" method="post" enctype="multipart/form-data">
          @csrf
      <button type="submit"  class="btn  custom-btn" >ZAPISZ</button>

      <div class="starter-template">

          @if (count($errors) > 0)
              <div class="alert alert-danger alert-dismissible fade show mt-4">
                  <strong>Whoops!</strong> There were some problems with your input.<br><br>
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
              <div class="col-md-8">
                  <div class="row">
                      <div class="col-md-2">
                          <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Tytuł</h6>
                      </div>

                      <input type="hidden"  value="{{$news->id}}" name="news_id">
                      <div class="col-md-10 " id="nazwa-field">
                          <div class="group">
                              <input type="text" name="title" value="{{$news->title}}" required>
                              <span class="highlight"></span>
                              <span class="bar"></span>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-2">
                          <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Treść</h6>
                      </div>
                      <div class="col-md-10">
                          <textarea  rows="10" cols="15" name="contents"  class="form-control mt-4" required>{{$news->contents}}</textarea>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-2">
                          <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Kategoria</h6>

                      </div>
                      <div class="col-md-10">
                          <div id="text-box">
                              <input type="radio" name="category" value="1" {{ ($news->newscategory_id ==  1  ) ? 'checked' : '' }} required>
                              <label id="label-styling"> Nowości</label>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-2">

                      </div>
                      <div class="col-md-5">
                          <div id="text-box1">
                              <input type="radio" name="category" value="2" {{ ($news->newscategory_id ==  2  ) ? 'checked' : '' }} required>
                              <label id="label-styling1"> Aktualności</label>
                          </div>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-md-2">
                          <h6 class="mt-4 text-justify text-color-primary" style="font-size: 18px">Zdjęcie</h6>
                      </div>
                      <div class="col-md-10">

                          <input type="file" id="myfile" name="image"  class="btn  custom-btn-2">

                      </div>
                  </div>



</div>

          </form>
      </div>

      </div>

  @endsection
