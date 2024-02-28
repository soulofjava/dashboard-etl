<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.front.meta')
    <title>Dashboard ETL</title>
    @include('layouts.front.style')
    @stack('css')
    <livewire:styles />
</head>

<body>
    <div class="content-wrapper">
        @include('layouts.front.header')
        <!-- /header -->
        {{ $slot ?? '' }}
        <!-- /section -->
        <livewire:front.list-kelurahan>
    </div>
    <!-- /.content-wrapper -->
    @include('layouts.front.footer')
    <livewire:scripts />
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    @include('layouts/front/js')
</body>

</html>
