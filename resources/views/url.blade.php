@extends('layouts.app')

@section('styles')

@endsection

@section('content')
    <div id="app" class="wrapper_url_shortener">
        <url-shortener authorized-user="{{ auth()->user()->id ?? null }}"></url-shortener>
    </div>
@endsection