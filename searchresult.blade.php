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


    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">


 
  <link href="https://code.jquery.com/jquery-3.5.1.js" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js" rel="stylesheet"/>
  <link href="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js" rel="stylesheet" />




    <link href="/css/hasnacompose.css" rel="stylesheet">
</head>


<body>


    <div class="wrapper-navbar">
        <nav class="navbar fixed-top navbar-expand-md custom-navbar navbar-dark">
            <img src="img/big-orange.jpg" alt="Description of the image" class="image-style">
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
        
        <div class="form-group has-search">
            <span class="fa fa-search form-control-feedback"></span>
            <input type="text" class="form-control" placeholder="Search">
        </div>
    </div>
    <!DOCTYPE html>
    <html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    * {box-sizing: border-box;}
    
    body {
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }
    
    .topnav {
      overflow: hidden;
      background-color: #ffffff;
      margin: 1pt;
      margin-bottom: -100pt;

    }
    

    

    
    .topnav .search-container {
      float: right;
      padding: 6px;
      margin-bottom: 100pt;
      margin-top: 100pt;
      margin-left: 1pt;
      margin-right: 1pt;
      

    }
    
    .topnav input[type=text] {
      padding: 5px;
      padding-bottom: 1px;
      margin-top: 5px;
      font-size: 17px;
      border-color: #ddd;
      background-color:#ddd;
      
      width: 1330px;
    }
    
    .topnav .search-container button {
      float: right;
      padding: 5px;
      padding-bottom: 1px;
      margin-top: 5px;
      margin-right: -80px;
      background: #ddd;
      font-size: 17px;
      border-color: #ddd;
      cursor: pointer;
      margin-left: 5px;
      width: 75px;

      
    }
    
    .topnav .search-container button:hover {
      background: #999999;
    }
    
    @media screen and (max-width: 600px) {
      .topnav .search-container {
        float: none;
      }
      .topnav a, .topnav input[type=text], .topnav .search-container button {
        float: none;
        display: block;
        text-align: left;
        width: 100%;
        margin: 0;
        padding: 14px;
      }
      .topnav input[type=text] {
        border: 1px solid #ccc;  
      }
    }
    </style>
    </head>
    <body>
    
    <div class="topnav">
      
      <div class="search-container" style="margin: 75pt">
        <form action="/action_page.php">
          <input type="text" placeholder="Search.." name="search">
          
          <button type="submit">Search</button>
        </form>
      </div>
    </div>
    
  
    
    </body>
    </html>
    

    <div class="wrapper">

  
          
    <table id="example" class="table table-striped table-bordered" style="margin: 20pt">
  <thead>
      <tr>
          <th>User ID</th>
          <th>Name</th>
          <th>Region</th>
          
      </tr>
  </thead>
  <tbody>
      <tr>
        <td>0346789</td>
        <td>Yuan Gito Saputra</td>
        <td>Bekasi</td>
      </tr>
      <tr>
        <td>0346799</td>
        <td>Heri Kurniawan</td>
        <td>Bogor</td>
        
      </tr>
      <tr>
        <td>0346899</td>
        <td>Hasna Tania</td>
        <td>Tangerang</td>
        
      </tr>
      <tr>
        <td>0346999</td>
        <td>Jon Ken</td>
        <td>Bandung</td>
        
      </tr>
      <tr>
        <td>0347999</td>
        <td>Lola Loli</td>
        <td>Lampung</td>
        
      </tr>
      <tr>
        <td>0348999</td>
        <td>Garet Eston</td>
        <td>Lampung</td>
      </tr>
      <tr>
         
  </tbody>
 
</table>


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
</body>


</html>
