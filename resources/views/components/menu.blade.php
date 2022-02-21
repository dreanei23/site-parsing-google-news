<ul class="list-reset lg:flex justify-end flex-1 items-center">
    @foreach ($menu as $item)
    <li class="mr-3">
        <a class="inline-block py-2 px-4 no-underline @if ($item->is_active) menu-link active @else menu-link @endif" href="{{ $item['route'] }}">{{ $item['name'] }}</a>
    </li>
    @endforeach
</ul>