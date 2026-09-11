@extends('layout.app')
@section('content')
<h2>{{ $title }}</h2>
<div class="card-wrapper">{!! $content !!}</div>
@endsection
