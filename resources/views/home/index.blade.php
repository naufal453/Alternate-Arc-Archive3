@extends('layouts.app-master')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@section('content')
    <style>
        .post-image {
            width: 200px;
            height: 300px;
            object-fit: cover;
            border-radius: 0.375rem;
        }

        @media (max-width: 576px) {
            .post-image {
                width: 50%;
                height: auto;
            }

            .fixed-card-size {
                height: 50%;
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }
        }

        .fixed-card-size {
            height: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .description-limit {
            display: -webkit-box;
            -webkit-line-clamp: 6;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>


    <div class="content-area p-5 rounded">
        <div class="position-relative mb-5">
            <div class="container px-0">
                <img class="img-fluid w-100 rounded shadow" style="max-height: 300px; object-fit: cover;"
                    src="{{ asset('images/banner.png') }}" alt="Alternate Arc Archive Banner">
            </div>
        </div>
        @include('user.user')
        @include('user.guest')
    </div>
@endsection
