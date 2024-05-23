@extends('layouts.app')
<div style="background-image: url('{{ asset('black') }}/img/backlog.jpg'); background-size: cover; background-position: top center;align-items: center;" data-color="purple"" >

@section('content')
    <div class="header py-7 py-lg-8">
        <div class="container">
            <div class="header-body text-center mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-8">
                         
                        <h1 class="text-white"><font size=+4><strong>{{ __('Sistema de Calidad') }}</strong></font></h1>
                       <!-- <p class="text-lead text-light">
                            {{ __('Use Black Dashboard theme to create a great project.') }}
                        </p>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
