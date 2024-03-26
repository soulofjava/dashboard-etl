<!DOCTYPE html>
<html lang="en">

<head>
    @include('back.includes.meta')

    <!-- PAGE TITLE HERE -->
    <title>Admin - Dashboard ETL</title>

    @stack('before-style') @include('back.includes.style')
    @stack('after-style') @vite([]) @livewireStyles
</head>

<body>
    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="waviy">
            <span style="--i: 1">L</span>
            <span style="--i: 2">o</span>
            <span style="--i: 3">a</span>
            <span style="--i: 4">d</span>
            <span style="--i: 5">i</span>
            <span style="--i: 6">n</span>
            <span style="--i: 7">g</span>
            <span style="--i: 8">.</span>
            <span style="--i: 9">.</span>
            <span style="--i: 10">.</span>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
        @include('back.includes.header') @yield('content')
        @include('back.includes.footer') @stack('before-script')
        @include('back.includes.script') @stack('after-script')
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->
    @livewireScripts
</body>

</html>