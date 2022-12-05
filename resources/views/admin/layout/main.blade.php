<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Panoptes Insight</title>

    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main/app-dark.css') }}">
    <link rel="shortcut icon" href="{{ asset('dist/assets/img/logo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('dist/assets/img/logo.png') }}" type="image/png">
    @yield('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div id="app">
        @include('admin.layout.sidebar')
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>@yield('title')</h3>
                            <p class="text-subtitle text-muted">@yield('sub-title','Halaman')</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <p>{{ request()->user()->name }}</p>
                            </nav>
                        </div>
                    </div>
                </div>
                @yield('card')
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">@yield('sub-title','Halaman')</h4>
                        </div>
                        <div class="card-body">
                            @yield('content')
                            <!-- This is where the content will be displayed -->
                        </div>
                    </div>
                </section>
            </div>

            @include('admin.layout.footer')

        </div>
    </div>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('js')

</body>

</html>
