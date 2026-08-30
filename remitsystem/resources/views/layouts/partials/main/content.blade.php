<main class="main">

    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        @section('breadcrumb')
            <li class="breadcrumb-item">Home</li>
    @show
    </ol>

    <div class="container-fluid">


            @yield('content')

    </div>
    <!-- /.conainer-fluid -->
</main>


<aside class="aside-menu">
</aside>
