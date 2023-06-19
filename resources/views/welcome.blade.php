<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Summarecon Email Blast</title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <link href="/css/hasna.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar fixed-top navbar-expand-md custom-navbar navbar-dark">
        <img src="hasna_img/img.navbar.jpg" alt="Description of the image" class="image-style">
        <button class="navbar-toggler navbar-toggler-right custom-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon "></span>
        </button>
        <div class="collapse navbar-collapse " id="collapsibleNavbar">
            <ul class="navbar-nav ml-auto ">
                <li class="nav-item">
                    <a class="nav-link" href="#offer"><b>HOME</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><b>HISTORY</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="openSearchBar()"><b>SEARCH</b></a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- <div class="search-bar" id="searchbar">
                <h1> testtttt </h1>
            </div> -->

        <div class="searchbar" id="searchbar">
            <div class="input-group">
        <input type="text" class="form-control" placeholder="Search Date">
        <div class="input-group-append">
            <button class="btn btn-secondary" type="button">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div></div>
    
    <div class="wrapper">

        <div class="wrapper-navbar-sidebar">

            <div class="sidebar">
                <div class="button-menu-select">
                    <ul class="menu">
                        <li><a href="#">Select Template</a></li>
                    </ul>
                </div>
                <div class="button-menu-select-2">
                    <ul class="menu">
                        <li><a href="#">Create Template</a></li>
                    </ul>
                </div>
                <!-- <ul class="menu">
                    <li><a href="#">Select Template</a></li>
                    <li><a href="#">Create Template</a></li>
                </ul> -->
            </div>

            <!--
            <div class="flex-center position-ref full-height">
                @if (Route::has('login'))
                    <div class="top-right links">
                        @auth
                            <a href="{{ url('/home') }}">Home</a>
                        @else
                            <a href="{{ route('login') }}">Login</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            -->


            <!--div class="links">
                        <a href="https://laravel.com/docs">Docs</a>
                        <a href="https://laracasts.com">Laracasts</a>
                        <a href="https://laravel-news.com">News</a>
                        <a href="https://blog.laravel.com">Blog</a>
                        <a href="https://nova.laravel.com">Nova</a>
                        <a href="https://forge.laravel.com">Forge</a>
                        <a href="https://vapor.laravel.com">Vapor</a>
                        <a href="https://github.com/laravel/laravel">GitHub</a>
                    </div-->
        </div>

        <!-- <div class="wrapper-contents"></div> -->

        <div class="contents">



            <div class="carousel-item">
                <img src="..." alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>...</h5>
                    <p>...</p>
                </div>
            </div>

            <!-- <h1>teststtttttycykhvjbdcjhbshvbclhabchjbchbashbchsbchbsjhbshjbsjhb</h1> -->
        </div>
    </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script>
        var counter = 0;

        function openSearchBar() {
            counter++
            console.log(counter)
            const search_box = document.getElementById("searchbar");
            if (counter % 2 == 0) {
                search_box.style.display = "none"
            } else {
                search_box.style.display = "block"
            }

        }
    </script>
</body>

</html>