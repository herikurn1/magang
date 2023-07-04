@extends('layouts.template')

@section('content')



<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Portal New Dashboard</li>
        </ol>
    </div>

    @if (Route::has('dashbboard'))
                <div class="top-right links">
                    @auths
                        <a href="{{ url('/home') }}">Home</a>
                    {{-- @else
                        <a href="{{ route('login') }}">Input Data</a>
                         --}}
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            {{-- @endif --}}
            

</div>
@endsection
