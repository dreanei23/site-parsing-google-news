@extends('web.common.app')

@section('h1', $news->title)

@section('content')

    {!! $news->content !!}

    @isset($news->source_url)
        <div class="text-base md:text-sm text-gray-500 py-6">
            <a x-data="{ url:'{{ $news->source_url }}' }" x-bind:href="url" target="_blank" class="text-base md:text-sm text-green-500 no-underline hover:underline">Источник</a>
        </div>
    @endisset

@endsection
