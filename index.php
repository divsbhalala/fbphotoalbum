<?php

require_once ("fbCredentials.php");
//$appId='1560505420851840';
//$appSecret='bdddcd25a93bb9ce8e5fcb564f9f9280';


?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Facebook Photo Album</title>

    <!-- Bootstrap Core CSS -->
    <link href="asset/css/bootstrap.min.css" rel="stylesheet">
    <link href="asset/css/fbalbum.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.9.0.min.js"></script>
    <script src="asset/js/fbscript.js"></script>


    <!-- Custom CSS -->
    <style>
    body {

        margin: 0%;
        /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
    }
    </style>
      <script>
       

        $(document).ready(function() {
            $('#aft-login').hide();

            $('#fbstylelog').hide();

            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

var fbAuthResp;
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '<?php echo $appId ?>',
                    cookie     : true,  // enable cookies to allow the server to access
                                        // the session
                    xfbml      : true,  // parse social plugins on this page
                    version    : 'v2.1' // use version 2.1
                });

                FB.getLoginStatus(function(response) {

                    if (response.status === 'connected')
                    {
                        if (response.authResponse) {
                            fbAuthResp = response;
                            //Set Accesstoken of user in session

                            $.ajax({
                                url: 'facebookalbum.php',
                                type: 'post',
                                data: {
                                    'accesstoken': response.authResponse.accessToken
                                },
                                success: function (data) {

                                }
                            });

                        }
                        $('#fbstylelog').hide();
                        $('#fbstyle').hide();
                        getuserprofile(response);
                        album(response);

                    }else if (response.status === 'not_authorized') {
                        // The person is logged into Facebook, but not your app.
                        $('#fbstyle').show();
                        $('#fbstylelog').hide();
                        console.log('Please log into this app.');
                    } else {
                        // The person is not logged into Facebook, so we're not sure if
                        // they are logged into this app or not.
                        $('#fbstyle').hide();
                        $('#fbstylelog').show();
                        console.log('Please log into facebook.');
                    }
                });

            };

            //make photo url
            $(".fbstyle").click(function() {
                FB.login(function(response) {
                    if (response.authResponse) {
                        var authResponse=response.authResponse;

                        getuserprofile(response);
                        album(response);


                    }
                }, {scope : 'email,user_photos'})
            });
        });
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
<div id="fb-root"></div>
<div class="container ">
    <div class="row">

    <!-- Navigation -->
    <nav class="navbar bg-info pagehero  col-lg-12 center-block" role="navigation">

            <div class="navbar-header">
               <h3 class="clearfix ">Facebook album</h3>
            </div>


        <!-- /.container -->
    </nav>

    </div>
</div>

    <!-- Page Content -->
    <div class="container ">

        <div class="row col-lg-12" id="login-div">

            <div class="col-lg-5">

                <button class="fbstyle btn-lg " id="fbstyle" >
                    Connect with Facebook
                </button>
                <button class="fbstyle btn-lg" id="fbstylelog">
                Login with Facebook
                </button>
                    <img class="loader">

            </div>
        </div>


        <!--        Model For Pop up Download        -->
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary btn-lg hidden" data-toggle="modal" data-target="#myModal" id="loaderbtn">
        </button>

        <!-- Modal -->
        <div class="modal fade " id="myModal" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-lg top">
                <div class="modal-content  radius0">

                    <div class="modal-body">
                        <button type="button" class="btn btn-default pull-right top close" data-dismiss="modal">X</button>
                        <div id="download-progress">
                        <h1>Please Wait while preparing your file</h1>

                        <img src="asset/img/loading1.gif" width="20%" height="20%" id="progress">
                        </div>
                        <div id="download-progress-done" >

                                 <h1>Click to download file</h1>
                            <div class="martop">
                                 <a href="#" class="btn-lg btn-primary radius0 " id="downloadLink" >Download</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- /.row -->


    <div class="row" id="aft-login">


                <div class="col-md-3  table-bordered center-block pull-left " id="profile">
                    <div class="pad0">
                    <img src="asset/img/bg.png" id="ProfilePic" class="center-block pad0">
                    </div>
                    <div id="username" class="pad2">
                    </div>
                    <div id="btngroup " class="container-fluid  ">
                        <button type="button" class="btn btn-success btn-group-justified  center-block  radius0 martop" id="download_all">Download All</button>
                        <button type="button" class="btn btn-success btn-group-justified  center-block  radius0 martop" id="download_album_select">Download selected</button>
                        <button type="button" class="btn btn-primary btn-group-justified  center-block  radius0 martop">Move All</button>
                        <button type="button" class="btn btn-primary btn-group-justified  center-block  radius0 martop" id="move_album_select">Move selected</button>
                        <button type="button" class="btn btn-warning btn-group-justified  center-block  radius0 martop" id="logout">Signout</button>
                    </div>

                </div>


        <div class="col-md-9 pull-left  " id="albumsss1">
        </div>
    </div>

</div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="asset/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="asset/js/bootstrap.min.js"></script>

</body>

</html>
