<div class="container w-full md:max-w-3xl mx-auto pt-20">

    <div class="w-full px-4 md:px-6 text-xl text-gray-800 leading-normal" style="font-family:Georgia,serif;">



        @unless(request()->routeIs('index'))
            {{ Breadcrumbs::render() }}
        @endunless

        <h1 class="font-bold font-sans break-normal text-gray-900 pt-6 pb-2 text-3xl md:text-4xl">
            @yield('h1')</h1>

        @hasSection('date_news')
            <p class="text-sm md:text-base font-normal text-gray-600">@yield('date_news')</p>
        @endif



        <div class="py-6">
            @yield('content')
        </div>

    </div>


</div>
