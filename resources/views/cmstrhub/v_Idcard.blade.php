<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="./Profile-card.css" /> -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        /* background: linear-gradient(#03a9f4, #03a9f4 45%, #fff 45%, #fff 100%); */
        }

        .card {
            position: relative;
            width: 400px;
            height: 500px;
            background: #fff;
            border-radius: 10px;
            /* background: rgba(255, 255, 255, 0.1); */
            /* background: linear-gradient(to bottom right, #ff3300, #ffa31a); */
            background-image: url("/trhub/image/backgr.png");
            border-top: 1px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(15px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
        }

        .logo{
            margin-top: 2rem;
            text-align: center;
            background: #ffffff;
            padding: 5px 0;
        }

        .kartu{
            text-align: center;
            padding-top: 1rem;
        }

        .kartu>span{
            font-size: 20px;
            font-family: 'Times New Roman', Times, serif;
        }

        .img-bx {
            position: absolute;
            top: 7rem;
            left: 0;
            text-align: center;
            width: 60%;
            height: 60%;
            border-radius: 10px;
            overflow: hidden;
            transform: translateY(30px) translateX(80px) scale(0.5);
            transform-origin: top;
        }

        .img-bx img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .nama{
            position: absolute;
            top: 10rem;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .content {
            position: absolute;
            width: 100%;
            /* height: 75%; */
            /* display: flex; */
            justify-content: center;
            align-items: flex-end;
            /* padding-bottom: 30px; */
            bottom: 15px;
        }

        .content .detail {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }

        .content .detail h2 {
            color: #000000;
            font-size: 1.6em;
            font-weight: bolder;
        }

        .content .detail h2 span {
            font-size: 0.6em;
            color: #000000;
        }

        .sci {
            position: relative;
            /* display: flex; */
            /* margin-top: 5px; */
            min-width: 100%;
        }

        .sci li {
            list-style: none;
            margin: 4px;
        }

        .sci li a {
            width: 45px;
            height: 45px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            background: transparent;
            font-size: 1.5em;
            color: #444;
            text-decoration: none;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
            transition: 0.5s;
        }

        .sci li a:hover {
            background: #03a9f4;
            color: #fff;
        }
    </style>
    <script src="https://kit.fontawesome.com/66aa7c98b3.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="card">
        <div class="logo">
            <img src="/img/smkg.png" alt="img" width="80"/>
        </div>
        <div class="kartu">
            <span>Kartu Identitas Karyawan Tenant</span>
        </div>
        <div class="img-bx">
            <img src="/img/download.jpg" alt="img" />
        </div>
        <!-- <div class="nama">
        </div> -->
        <div class="content">
            <div class="detail">
                <h2>Jhon Doe<br />
                    <span>Periode : Jan - Mar 2022</span>
                </h2>
                <!-- <div class="barcode">
                    <div class="row">
                        <div class="col-md-6">
                        <img src="/trhub/image/Icon.png" alt="img" width="50"/>
                        </div>
                    </div>
                </div> -->
                <ul class="sci">
                <!-- <li>
                    <img src="/trhub/image/Icon.png" alt="img" width="50"/>
                </li> -->
                <li>
                    <img src="/trhub/id-karyawan/qrcode/TRHUB202212002.svg" alt="img" width="100"/>
                </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>