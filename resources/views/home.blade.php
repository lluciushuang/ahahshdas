@extends('base.base')
@section('content')
    <h1>My Homepage</h1>
    <p>Product Category: {{ $product_category }}</p>
    <p>Product Name: {{ $product_name }}</p>
    <img src="{{ asset('images/kucing.webp') }}" alt="">
    {!! $button !!}
@endsection