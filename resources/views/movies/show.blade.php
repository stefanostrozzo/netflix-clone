@extends('layout')
@section('content')
<div class="container-fluid mx-auto p-4 h-screen" style="height: 100vh;">
    <iframe class="w-full h-[80vh]" style="height: 80vh;" src="https://vixsrc.to/movie/{{ $movie['id'] }}?autoplay=false&lang=it&primaryColor=B20710"
    title="Video player" frameborder="0" allow="accelerometer; autoplay=false; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
    allowfullscreen></iframe>
</div>
@endsection