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


    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->




    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->


    <link href="/css/hasna_css/compose.css" rel="stylesheet">
</head>

<body>

<div class="wrapper1">
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
            </nav>
</div>
    

    <div class="search-bar" id="searchbar">
            <div class="input-group">
        <input type="text" class="form-control" placeholder="Search Date">
        <div class="input-group-append">
            <button class="btn btn-secondary" type="button">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div></div>
</div>

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
        </div>

        <!-- <div class="contents2">
            <h1>Create Template</h1> -->
        <div class="headline-drafts">
            <h1>Composing Email</h1>
            <div class="contents2">
                <main class="login-form">
                    <!-- <div class="container" style="border-radius: 10px";> -->
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <!-- <div class="card-header" style="font-weight: bold">Create Template</div> -->
                                <div class="card-body">



                                    <!-- <body id="LoginForm"> -->
                                    <!-- <div class="container"> -->

                                    <!-- <div class="login-form-body"> -->
                                    <div class="main-div">
                                        <!-- <div class="panel">

                                                </div> -->
                                        <form id="Login" action="/compose" method="POST">
@csrf
                                            <div class="form-group">


                                                <input name="title" type="title" class="form-control " id="inputTitle" placeholder="Title">

                                            </div>

                                            <div class="form-group">

                                                <input name="subject" type="subject" class="form-control" id="inputSubject" placeholder="Subject">

                                            </div>

                                            <div class="form-group">


                                                <input name="from_email" type="email" class="form-control" id="inputEmail" placeholder="From">

                                            </div>

                                            <div class="form-group">


                                                <textarea name="from" class="form-control2" id="from" placeholder="To" cols="120" rows="5" style="animation: reverse"></textarea>

                                            </div>

                                            <div class="form-group">


                                                <textarea name="bcc" class="form-control2" id="bcc" cols="120" rows="5" placeholder="Bcc/Cc"></textarea>

                                            </div>

                                    </div>

<!-- 
                                    </form>
                                    <textarea name="Article_content" id="Article_editor"></textarea> -->



                                </div>
                            </div>
                        </div>
                    </div>

                    <!--
                        <form action="" method="">
                            <div class="form-group row">
                                <label for="title" class="col-md-2 col-form-label text-md-right">Title</label>
                                <div class="col-md-6">
                                    <input type="text" name="Article_title" id="title">
                                    
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="subject" class="col-md-2 col-form-label text-md-right">Subject</label>
                                <div class="col-md-6">
                                    <input type="text" name="Article_title" id="title">
                                </div>
                            </div>
                            -->


            </div>


            <div class="contents3">
                <div class="row mt-4 plus float-right" style="margin-right: 1px" ;>
                    <div class="col">
                        <button class="col btn btn-green-moon btn-rounded" style="font-weight: bold">Save as Draft</button>

                    </div>


                    <div class="col">
                        <button class="col btn btn-orange-moon btn-rounded" style="font-weight: bold">Send
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    </div>
    </div>
    </div>
    </div>
    </div>

    </main>
    </div>



    </div>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <!doctype html>
    <html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="css/style.css">

        <link rel="icon" href="Favicon.png">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

        <title>Laravel</title>
    </head>

    <body>
        <!--
<nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="#">Laravel</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Register</a>
                </li>
            </ul>

        </div>
    </div>
</nav>
-->


    </body>

    </html>
    <script src="ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('Article_editor');
    </script>
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