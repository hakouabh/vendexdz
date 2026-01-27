@extends('layouts.appgent')

@section('title', 'Orders - Vendex')

@section('content')
    @livewire('v2.order.orders', ['context' => request()->segment(1)])
@endsection