@extends('layouts.app')

@section('content')
    <form class="form-signin text-center" method="POST" action="{{ route('login') }}">

        @csrf


        <img style="width: 99px;height: 112px;" class="mb-5 " src="/public/assets/img/logo.png" alt="" width="72" height="72">
        <div class="group">
            <input type="email" class="@error('email') is-invalid @enderror"  name="email" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label>E-mail</label>
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>

        <div class="group" style="margin-top:-2rem">
            <input type="password" class=" @error('password') is-invalid @enderror" name="password" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="text-center">Haslo</label>
            @error('password')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>

        <button type="submit"  style="background: #6185FD;color:white" class="btn btn-lg btn-primary btn-block signin-button" type="submit">Zaloguj</button>
    </form>
@endsection
