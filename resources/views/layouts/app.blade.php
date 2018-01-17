<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @yield('meta')

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            @hasSection('title')
                @yield('title') - {{ config('app.name', 'Laravel') }}
            @else
                {{ config('app.name', 'Laravel') }}
            @endif
        </title>

        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

        <!-- https://laravel.com/docs/5.5/blade#stacks -->
        @stack('head-scripts')
    </head>

    <body>
        @include('partials.navigation')

        <main role="main" id="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 {{ $__env->yieldContent('side-content') ? 'col-lg-8 ' : '' }} d-flex flex-column content-container @if ($__env->yieldContent('main-content-classes'))@yield('main-content-classes')@endif">
                        @yield('main-content')
                    </div>

                    @if ($__env->yieldContent('side-content'))
                        <div class="col-sm-12 col-lg-4 bg-light d-flex flex-column chat-container @if ($__env->yieldContent('side-content-classes'))@yield('side-content-classes')@endif">
                            @yield('side-content')
                        </div>
                    @endif
                </div>
            </div>
        </main>

        @include('partials.js_bind')

        <!-- Global Scripts -->
        <script src="{{ mix('js/manifest.js') }}"></script>
        <script src="{{ mix('js/vendor.js') }}"></script>

        <!-- App Scripts -->
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
