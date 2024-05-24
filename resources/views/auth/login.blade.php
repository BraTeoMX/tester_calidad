@extends('layouts.app', ['class' => 'login-page', 'page' => __('Sistema de Calidad'), 'contentClass' => 'login-page'])
<div style="background-image: url('{{ asset('black') }}/img/backlog.jpg'); background-size: cover; background-position: top center;align-items: center;"
    data-color="purple"">

    @section('content')

        <div class="col-lg-4 col-md-6 ml-auto mr-auto">
            <form class="form" method="post" action="{{ route('login') }}">
                @csrf

                <div class="card card-login card-white">
                    <div class="card-header card-header-success text-center">
                        <h1 class="card-title text-secondary">{{ __('Login') }}</h1>

                    </div>
                    <div class="card-body">
                        <div class="input-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-email-85"></i>
                                </div>
                            </div>
                            <input type="email" name="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Email') }}">
                            @include('alerts.feedback', ['field' => 'email'])
                        </div>
                        <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-lock-circle"></i>
                                </div>
                            </div>
                            <input type="password" placeholder="{{ __('Password') }}" name="password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                            @include('alerts.feedback', ['field' => 'password'])
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" href=""
                            class="btn btn-secondary btn-lg btn-block mb-1">{{ __('Aceptar') }}</button>
                    </div>
                </div>
            </form>
        </div>
    @endsection
