// to store all albums IDs
//@param
var albumids = new Array();

// to store all selected albums IDs
var albumidsselect = new Array();

var fbAuthResp;


$(document).ready(function() {
        $("#loaderbtn").hide();
        $("#download-progress-done").hide();

    $('#download_all').click(function(event)
    {
        event.preventDefault();

        $("#download-progress-done").hide();
        $("#download-progress").show();
       // $("#loaderbtn").click();
        downloadallalbum(albumids);
    });

    // Download all selected albums
    $('#download_album_select').click(function(event) {
        event.preventDefault();


        $("#download-progress-done").hide();
        $("#download-progress").show();
        //Album ids to array for download
        var i = 0;
        $('.checkboxSelect:checked').each(function() {

            albumidsselect[i] = $(this).val();
            i++;

        });
        if (albumidsselect.length > 0) {

            downloadallalbum(albumidsselect);
            //albumidsselect='';
        } else {
            alert("No album selected");
        }
    });

    // Move all selected albums
    $('#move_album_select').click(function(event) {
        event.preventDefault();
        //Album ids to array for download
        var i = 0;
        $('.checkboxSelect:checked').each(function() {

            albumidsselect[i] = $(this).val();
            i++;

        });
        if (albumidsselect.length > 0) {
            moveallalbum(albumidsselect);
            //albumidsselect="";
        } else {
            alert("No album selected");
        }
    });

    //Logout
    $('#logout').click(function() {
        FB.logout(function(response) {
            // user is now logged out
            window.location.reload();

        });
    });

});

function album(response)
{
    FB.api('/me/albums', function(response) {
        $.each(response.data,function(key,value){

            //for download album
            albumids[key] = value.id;
            var strHtml = '' + '<div id="album_' + key + '" class="col-md-3 pad2"> ' + '<a href="#" class="album_link_' + key + '"><img style="height:200px;width:200px;" class="imgcover" id="album_cover_' + key + '"src="asset/img/loading.gif" /></a>' + '<div class="table-bordered mar2"><input class="checkboxSelect" id="checkbox_' + key + '" type="checkbox" value="' + value.id + '"><a for="checkbox_' + key + '" href="#" class="album_link_' + key + '"><h5>' + value.name + '</h5></a><label class="subheader">' + value.count + ' photos</label><ul class="button-group"><li><a title="Download" id="download_album_' + key + '" class="btn btn-success glyphicon-download radius0" ></a></li><li><a title="Move to Picasa" id="move_album_' + key + '" class="btn btn-success radius0" >Move</a></li></ul>' + '</div></div>';
            $('#albumsss1').append(strHtml);
            FB.api('/' + value.cover_photo + '', function(response) {
                if (!response.picture) {
                    $('#album_' + key).hide();
                } else {
                    $('#loading_' + key).hide();
                    $('#album_cover_' + key).attr("src", response.picture);
                }
            });
        });

    });
}
function getuserprofile(response)
{
    FB.api('/me', function(response) {

        $('#login-div').hide();
        $('#aft-login').show();

        $('#ProfilePic').attr('src', 'http://graph.facebook.com/' + response.id + '/picture?width=500&height=500');
        $('#ProfilePic').css('width','100%');
        document.getElementById('username').innerHTML = response.name;


    });

}

function downloadallalbum(albumIds)
{
    $("#loaderbtn").click();
    $.ajax({
        url : 'facebookalbum.php?albumids=' + albumIds,
        type : 'get',
        success : function(data) {
            //get userid from facebook api
            FB.api('/me', function(response) {
                //show download button
                $("#download-progress").hide();
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

function moveallalbum(albumids)
{
    $("#loaderbtn").click();
   // alert("move all album "+ albumids);

}