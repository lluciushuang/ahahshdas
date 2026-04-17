@extends('base.base')
@section('content')
    <h1>My Homepage</h1>
    <p>Product Category: {{ $product_category }}</p>
    <p>Product Name: {{ $product_name }}</p>
    <img src="img/aba.jpg", alt="ini foto">
    {!! $button !!}
@endsection