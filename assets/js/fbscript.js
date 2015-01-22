/**
 * Created by sweet on 17/1/15.
 */

// to store all albums IDs
var albumids = new Array();

// to store all selected albums IDs
var albumidsselect = new Array();

var fbAuthResp;


$(document).ready(function(){

    $('#supersized-loader').hide();
    $('#supersized').hide();
    $('#aft_login').hide();


//Click To logout
    $('#signout').click(function() {
        FB.logout(function(response) {
            // user is now logged out
            window.location.reload();
            $('#bef_login').show();
            $("#aft_login").hide();
        });
    });


    //Download All Selected

    $('#download_selected').click(function(){
        event.preventDefault();
        //Album ids to array for download
        var i = 0;
        $('.checkboxSelect:checked').each(function() {

            albumidsselect[i] = $(this).val();
            i++;

        });
        downloadselected(albumidsselect);
    });

    //Move All Selected

    $('#move_selected').click(function(){
        alert('move_selected');
    });

    //Download All

    $('#download_all').click(function(){
        alert('download all');
    });

    //Move All

    $('#move_all').click(function(){
        alert('move all');
    });




    //$('')
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
        appId      : '1560505420851840',
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
            $('#bef_login').hide();
            $('#aft_login').show();
            getuserprofile(response);
            album(response);

        }else if (response.status === 'not_authorized') {
            // The person is logged into Facebook, but not your app.
           // $('#fbstyle').show();
            //$('#fbstylelog').hide();
            console.log('Please log into this app.');
        } else {
            // The person is not logged into Facebook, so we're not sure if
            // they are logged into this app or not.
           // $('#fbstyle').hide();
            //$('#fbstylelog').show();
            console.log('Please log into facebook.');
        }
    });

};

    $(".btn_login").click(function() {
    FB.login(function(response) {
        if (response.authResponse) {
            var authResponse=response.authResponse;
            $('#bef_login').hide();
            $('#aft_login').show();

            getuserprofile(response);
            album(response);


        }
    }, {scope : 'email,user_photos'})
});


    function getuserprofile(response)
    {
        FB.api('/me', function(response) {

            $('#ProfilePic').attr('src', 'http://graph.facebook.com/' + response.id + '/picture?width=500&height=500');
           // $('#ProfilePic').css('width','100%');
            document.getElementById('username').innerHTML = response.name;


        });

    }

    function album(response)
    {
        FB.api('/me/albums', function(response) {
            $.each(response.data,function(key,value){


                //for download album
                albumids[key] = value.id;
                var strHtml = '' + '<div id="album_' + key + '" class="large-4 medium-4 album left"> ' + '<a href="#" class="album_link_' + key + '"><img style="height:174px;width:174px;" class="imgcover" id="album_cover_' + key + '"src="assets/img/loading.gif" /></a>' + '<div class="album_info"><input class="checkboxSelect" id="checkbox_' + key + '" type="checkbox" value="' + value.id + '"><a for="checkbox_' + key + '" href="#" class="album_link_' + key + '"><h6>' + value.name + '</h6></a><label class="subheader">' + value.count + ' photos</label><div class="text-center"><ul class="button-group"><li><a title="Download" id="download_album_' + key + '" class="tiny button fi-download size-12" ></a></li><li><a title="Move to Picasa" id="move_album_' + key + '" class="tiny button" >Move</a></li></ul></div>' + '</div></div>';
                $('#albumsss1').append(strHtml);
               // alert(value.cover_photo+' '+value.id);
                FB.api('/' + value.cover_photo + '', function(response) {
                    if (!response.picture) {
                        $('#album_' + key).hide();
                    } else {
                        $('#loading_' + key).hide();
                    ///   alert(response.source)

                        $('#album_cover_' + key).attr("src", response.picture);
                    }

                    //Show albums photos in gallery
                    $('.album_link_' + key).click(function(event) {
                        event.preventDefault();
                        show_albums_photos(value.id);
                    });
                });
            });

        });
    }

    function show_albums_photos(albumid){

        $('#loading_gallery').show();
        $('#aft_login').hide();
        $('.top-bar').hide();
       // alert($('#album_' + albumid).length);
        if ($('#album_' + albumid).length > 0) {

            $('#album_' + albumid).show();
        }
        else
        {
            FB.api('/' + albumid + '/photos', function(response) {
                var arrPhotos = [];
                alert(response.data.length)
                // console.log(response.data);
                $.each(response.data, function(key, value) {
                    arrPhotos.push({
                        image : value.source,
                        title : (value.name != undefined) ? value.name : '',
                        thumb : value.picture,
                        url : value.link
                    })
                });
              //  $('#loading_gallery').hide();
                jQuery(function($) {

                    $.supersized({
                        slide_interval : 8000, // Length between transitions
                        transition : 1, // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
                        transition_speed : 700, // Speed of transition
                        // Components
                        slide_links : 'blank', // Individual links for each slide (Options: false, 'num', 'name', 'blank')
                        slides : arrPhotos

                    });
                });
            });

            $('#supersized-loader').show();
            $('#supersized').show();

            $('#slider').show();


        }


    }

    $('#backtoalbum').click(function(){

        $('#slider').hide();

        $('#aft_login').show();
        $('.top-bar').show();

        $("#thumb-list").remove();
        $("#supersized").html('');

        $('#supersized-loader').hide();
        $('#supersized').hide();


    });


    //download selected album
    function downloadselected(albumidsselect)
    {
        $('#openmodal').click();
        $.ajax({
            url : 'facebookalbum.php?albumids=' + albumidsselect,
            type : 'get',
            success : function(data) {
                //get userid from facebook api
                FB.api('/me', function(response) {
                    //show download button
                    $("#downloadprogress").hide();
                    $("#download-progress-done").show();
                    $("#downloadLink").attr('href', response.id + '.zip');
                });

            },
            error : function(data) {
                //Handle error
                alert('Error Occure on server,Please Try again')
            }
        });
    }
});

