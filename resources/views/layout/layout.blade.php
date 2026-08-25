<!DOCTYPE html>
<html class="no-js" lang="en">

    <!-- Start head Section -->
    <x-head />
    <!-- End head Section -->

<body>

    {{-- Hijo directo de <body>, fuera de #scrollsmoother-container: cualquier
         `position: fixed` dentro de un contenedor con `transform` (como el que
         aplica GSAP ScrollSmoother) deja de ser fijo respecto al viewport. Las
         páginas que necesiten pintar algo por encima de todo (ej. el preloader
         propio de Fermento) lo declaran aquí en vez de dentro de @yield('content'). --}}
    @yield('body-start')

    <!-- Start preloader  -->
    <x-preloader />
    <!-- End preloader  -->

    <!-- Start Header Section -->
    <x-header />
    <!-- End Header Section -->

    <div id="scrollsmoother-container">
        <!-- Start Hero -->
        <x-hero title='{{ isset($title) ? $title : "" }}' subTitle='{{ isset($subTitle) ? $subTitle : "" }}'/>
        <!-- End Hero -->

        @yield('content')

        <!-- Start Footer -->
        <x-footer />
        <!-- End Footer -->
    </div>

    <div class="loading-overlap"></div>
    <!-- End Footer -->

    <!-- Start scrollup Section -->
    <x-scrollup />
    <!-- End scrollup Section -->

    <!-- Start Video Popup -->
    <x-videopopup />
    <!-- End Video Popup -->

    <!-- Start script Section -->
    <x-script />
    <!-- End script Section -->

</body>

</html>