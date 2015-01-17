<?php
$appId='485247754943027';
$appSecret='e1abda0f213cc9dbe60c236c8be684ab';


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
            function makeFacebookPhotoURL( id, accessToken ) {
                return 'https://graph.facebook.com/' + id + '/picture?access_token=' + accessToken;
            }

            function getphotofromalbumid(albumid, response)
            {
                FB.api('/'+albumid+'/photos', function(response) {
                    if(response.data && response.data.length)
                    {
                       
                    }

                });
            }

           
            function album(response)
            {
                FB.api('/me/albums?fields=id,name', function(response) {
                    for (var i=0; i<response.data.length; i++) {
                        var album = response.data[i];
                        var divid=response.data[i].id.toString();
                        var div = document.createElement('div');
                        div.id=divid;
                       div.className='col-lg-2';
                             FB.api('/'+album.id+'/photos?type=large', function(photos){
                                        var photo = photos.data[0];
                                        var image = document.createElement('img');
                                        image.src = photo.picture;
                                        document.getElementById(divid).appendChild(image);
                            });

                        document.getElementById("albumsss").appendChild(div)
                    }
                });
            }
            function getuserprofile(response)
            {
                FB.api('/me', function(response) {

                    $('#login-div').hide();
                    $('#aft-login').show();

                    $('#ProfilePic').attr('src', 'http://graph.facebook.com/' + response.id + '/picture?type=large');
                    $('#ProfilePic').css('width','100%');
                    document.getElementById('username').innerHTML = response.name;


                });

            }
            $(".fbstyle").click(function() {
                FB.login(function(response) {
                    if (response.authResponse) {
                        var authResponse=response.authResponse;

                        getuserprofile(response);
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

        <!-- /.row -->


    <div class="row" id="aft-login">
        <div class="row  ">

                <div class="col-lg-3  table-bordered center-block pull-left" id="profile" >
                    <div class="pad0">
                    <img src="asset/img/progress.gif" id="ProfilePic" class="center-block glyphicon-ban-circle">
                    </div>
                    <div>
                        <h1 id="username"></h1>
                    </div>

                </div>
                <div class="col-lg-9" id="albumsss">


                </div>
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
