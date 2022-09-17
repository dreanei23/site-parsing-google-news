@extends('web.common.app')

@section('h1', $category->name)

@section('content')

    @foreach ($news as $item)
        <div class="p-8 mt-6 mb-8 lg:mt-0 leading-normal rounded shadow bg-white">
            <div class="mb-2 text-sm text-gray-600">{{ $item->created_at->format('d.m.Y, h:i') }}</div>
            <a href="{{ route('news.show', ['category' => $category->slug, 'news' => $item->slug]) }}">{{ $item->title }}</a>
        </div>
    @endforeach

    {{ $news->links() }}

@endsection
