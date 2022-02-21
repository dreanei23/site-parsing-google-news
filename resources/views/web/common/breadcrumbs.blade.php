@unless ($breadcrumbs->isEmpty())
<nav>
    <ol class="list-reset flex md:text-sm">
        @foreach ($breadcrumbs as $breadcrumb)

            @if (!is_null($breadcrumb->url) && !$loop->last)
            <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            <li><span class="text-gray-600 mx-2">/</span></li>
            @else
            <li class="text-gray-600">{{ $breadcrumb->title }}</li>
            @endif

        @endforeach
    </ol>
    </nav>
@endunless