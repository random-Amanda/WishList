<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Bootstrap core CSS -->
    <link href="application\libraries\css\bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400|Poppins:300,400,800&display=swap" rel="stylesheet">
    <!-- load Jquery,underscore,backbone -->
    <script src="application\libraries\js\jquery.js" type="text/javascript"></script>
    <script src="application\libraries\js\underscore.js" type="text/javascript"></script>
    <script src="application\libraries\js\backbone.js" type="text/javascript"></script>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Lato:300,400|Poppins:300,400,800&display=swap');

        #overlay {
            position: fixed;
            display: none;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2;
            cursor: pointer;
        }

        .container {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            /* //overflow: auto; */
            background-color: "black";
        }

        .infoi {
            position: relative;
            z-index: 10;
        }

        .animatedview {
            animation-delay: 1s;
            flex-direction: column;
            animation: fadeImg 1s ease-in-out forwards;
            animation: showDisc 1s cubic-bezier(.74, .06, .4, .92) forwards;
        }

        .disabledbutton {
            pointer-events: none;
            opacity: 0.4;
        }
    </style>
</head>

<body>
    <div id="overlay">

        <!-- item view -->
        <div id="itemview" class="container-fluid infoi formview mx-auto my-5 bg-light col-sm-6 p-3 lead text-dark" style="margin:auto">
        </div>

        <!-- share view -->
        <div id="shareview" class="container-fluid infoi formview mx-auto my-5 bg-light col-sm-6 p-3 lead text-dark" style="margin:auto">
        </div>

        <!-- timeout login -->
        <div id="timeoutLogin" class="container-fluid infoi formview mx-auto my-5 bg-light col-sm-6 p-3 lead text-dark" style="margin:auto">
        </div>

        <!-- add item form -->
        <div id="additemform" class="container-fluid infoi formview mx-auto my-5 bg-light col-sm-6 p-3 lead text-dark" style="margin:auto">
            <table style="width: 98%;margin-left:1%;margin-right:1%;">
                <tr>
                    <td VALIGN=TOP>
                        <svg id="closeadditem" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-x-circle-fill" viewBox="0 0 16 16"> +

                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />' +

                        </svg>
                    </td>
                    <td>
                        <form>
                            <div class="form-row">
                                <h1 class="h1 float-left m-5 text-dark">Add Item</h1>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" placeholder="Your wish.." required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="img">Picture</label>
                                    <input type="text" class="form-control" id="img" placeholder="URL of image">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text" class="form-control" id="url" placeholder="Where to find it" required>
                            </div>
                            <div class="form-group">
                                <label for="itemdesc">Description</label>
                                <input type="text" class="form-control" id="itemdesc" placeholder="notes..">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="priority">Priority</label>
                                    <select id="priority" class="form-control" required>
                                        <option selected>Must-Have</option>
                                        <option>Would be Nice to Have</option>
                                        <option>If you can</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="qty">Quantity</label>
                                    <select id="qty" class="form-control" required>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="price">Price</label>
                                    Rs.<input type="text" class="form-control" id="price" required>
                                </div>
                            </div>
                            <button id="additemformbtn" type="submit" class="btn btn-primary">Add Item</button>
                        </form>
                    </td>
                </tr>
            </table>
        </div>

        <!-- edit item form -->
        <div id="edititemform" class="container-fluid infoi formview mx-auto my-5 bg-light col-sm-6 p-3 lead text-dark" style="margin:auto">
            <table style="width: 98%;margin-left:1%;margin-right:1%;">
                <tr>
                    <td VALIGN=TOP>
                        <svg id="closeedititem" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-x-circle-fill" viewBox="0 0 16 16"> +

                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />' +

                        </svg>
                    </td>
                    <td>
                        <form>
                            <div class="form-row">
                                <h1 class="h1 float-left m-5 text-dark">Edit Item</h1>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="titleedit" placeholder="Your wish..">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="img">Picture</label>
                                    <input type="text" class="form-control" id="imgedit" placeholder="URL of image">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text" class="form-control" id="urledit" placeholder="Where to find it">
                            </div>
                            <div class="form-group">
                                <label for="itemdesc">Description</label>
                                <input type="text" class="form-control" id="itemdescedit" placeholder="notes..">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="priority">Priority</label>
                                    <select id="priorityedit" class="form-control">
                                        <option>Must-Have</option>
                                        <option>Would be Nice to Have</option>
                                        <option>If you can</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="qty">Quantity</label>
                                    <select id="qtyedit" class="form-control">
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="price">Price</label>
                                    Rs.<input type="text" class="form-control" id="priceedit">
                                </div>
                            </div>
                            <button id="edititemformbtn" type="submit" class="btn btn-primary">Edit Item</button>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div id="thetop">

        <!-- login form -->
        <div id="loginform" class=" formview mx-auto  col-sm-6  lead text-dark my-5">
            <h1 class="display-4  m-5 text-dark">Login</h1>
            <form>
                <div class="form-group row">
                    <label for="name" class="col-sm-6 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input id="loginusername" type="text" class="form-control" name="name" placeholder=" Enter username" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-sm-6 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input id="loginpsw" type="password" class="form-control" name="password" placeholder="Enter password" required>
                    </div>
                </div>
                <div id="loginerror" class="text-danger"></div><br>
                <button id="loginformbtn" type="button" class="btn btn-success">Login</button>
                <button type="submit" id="signupbtn" class="btn btn-success">Signup </button>
            </form>
        </div>

        <!-- signup form -->
        <div id="signupform" class=" formview mx-auto  col-sm-6  lead text-dark my-5">
            <h1 class="display-4  m-5 text-dark">Signup</h1>
            <form>
                <div class="form-group row">
                    <label for="name" class="col-sm-6 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input id="createusername" type="text" class="form-control" name="name" placeholder="e.g. janedoe@example.com" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-sm-6 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input id="createpsw" type="password" class="form-control" name="password" placeholder="Enter password" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-sm-6 col-form-label">List name</label>
                    <div class="col-sm-10">
                        <input id="listname" type="text" class="form-control" name="name" placeholder="e.g. My Birthday Wish List" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-6 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <input id="listdesc" type="text" class="form-control" name="description" placeholder="e.g.What I want for my birthday." required>
                    </div>
                </div>
                <div id="signuperror" class="text-danger"></div><br>
                <button id="signupBack" type="button" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                    </svg> Back <span class="fa fa-arrow-right"></span></button>
                <button type="submit" class="float-right btn btn-success">Signup <span class="fa fa-arrow-right"></span></button>
            </form>
        </div>

        <table style="width: 80%;margin-left:1%;margin-right:10%;margin-top:10%;">
            <tr>
                <td style="width: 30%;" VALIGN=TOP>
                    <!-- list view -->
                    <div id="listview" class=" view mx-5  lead text-dark stick-top">
                    </div><br>
                    <table style="width:70%;margin-left:10%;margin-right:20%;">
                        <td><a id="additembtn" class="btn btn-primary ml-4">Add Item</a></td>
                        <td><a id="sharebtn" class="btn btn-primary">Share list</a></td>
                    </table>
                </td>
                <td style="width: 65%;text-align:center;">
                    <h5 id="itemsheading" class=" view">Your Wishes..</h5>
                    <!-- items in list view  -->
                    <div id="itemsview" class="my-5 rounded-circle view mx-auto lead text-dark ">
                    </div>
                </td>
                <td>
                    <!-- profile view -->
                    <div id="profile" class=" view mx-5 h4 text-dark" style="position:absolute;top:0;right:0;">
                    </div>
                </td>
            </tr>
        </table>

    </div>
</body>
<script>
    //set Authorization header before sending reaquest
    $.ajaxSetup({
        beforeSend: function(xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer '.concat(sessionStorage.getItem('token')));
        }
    });
    var User = Backbone.Model.extend({
        defaults: {
            "userName": null,
            "description": null,
            "loggedIn": null,
            "lid": null
        }
    });
    var user = new User();
    var List = Backbone.Model.extend({
        defaults: {
            "listdesc": null,
            "listName": null,
            "items": null
        }
    });
    var list = new List();
    $(document).ready(function() {
        initialize();

        function initialize() {
            $(".formview").hide();
            $(".view").hide();
            // check if logged in
            if (sessionStorage.getItem("uid")) {
                loadProfile();
                loadUserList();
            } else {
                $("#loginform").show();
                $("#additembtn").hide();
                $("#sharebtn").hide();
            }
        }

        $('#signupbtn').click(function() {
            $('#signupform').trigger("reset");
            $(".formview").hide();
            $("#signupform").show();
        });

        $('#signupBack').click(function() {
            $('#loginform').trigger("reset");
            $(".formview").hide();
            $("#loginform").show();
        });

        // login
        $('#loginformbtn').click(function(event) {

            $('#loginerror').html("");
            var formData = {
                'userName': $('#loginusername').val(),
                'password': $('#loginpsw').val()
            };
            $.ajax({
                    type: 'PUT',
                    url: '<?= site_url('api/user') ?>',
                    data: formData,
                    dataType: 'json',
                    encode: true
                })
                .done(function(response) {
                    // get user details
                    sessionStorage.setItem("token", response.token);
                    fetchUserData(response.uid);
                })
                .fail(function(data, textStatus, xhr) {
                    var response = JSON.parse(data.responseText);
                    $('#loginerror').html(response.message);
                });
            event.preventDefault();
        });

        // sign-up
        $('#signupform').submit(function(event) {

            $('#signuperror').html("");
            // create user
            var formData = {
                'userName': $('#createusername').val(),
                'password': $('#createpsw').val()
            };
            $.ajax({
                    type: 'POST',
                    url: '<?= site_url('api/user') ?>',
                    data: formData,
                    dataType: 'json',
                    encode: true
                })
                .done(function(response) {
                    $.ajax({
                            type: 'PUT',
                            url: '<?= site_url('api/user') ?>',
                            data: formData,
                            dataType: 'json',
                            encode: true
                        })
                        .done(function(response) {
                            // get user details
                            sessionStorage.setItem("token", response.token);
                            // create list
                            var payload = {
                                'listName': $('#listname').val(),
                                'listdesc': $('#listdesc').val(),
                                "uid": response.uid
                            };
                            $.ajax({
                                    type: 'POST',
                                    url: '<?= site_url('api/wishlist') ?>',
                                    data: payload,
                                    dataType: 'json',
                                    encode: true
                                })
                                .done(function(data) {
                                    // get user details
                                    fetchUserData(response.uid);
                                })
                                .fail(function(data, textStatus, xhr) {
                                    var response = JSON.parse(data.responseText);
                                    $('#signuperror').html(response.message);
                                    if (xhr == 'Unauthorized') {
                                        alert("Session expired. Please Log-in again.");
                                        sessionStorage.clear();
                                        initialize();
                                    }
                                });
                        })
                        .fail(function(data, textStatus, xhr) {
                            var response = JSON.parse(data.responseText);
                            $('#loginerror').html(response.message);
                        });
                })
                .fail(function(data, textStatus, xhr) {
                    var response = JSON.parse(data.responseText);
                    $('#signuperror').html(response.message);
                });
            event.preventDefault();
        });

        // addItem
        $('#additemform').submit(function(event) {

            var formData = {
                'title': $('#title').val(),
                'price': $('#price').val(),
                'itemdesc': $('#itemdesc').val(),
                'url': $('#url').val(),
                'img': $('#img').val(),
                'qty': $('#qty').val(),
                'lid': sessionStorage.getItem("lid"),
                'uid': sessionStorage.getItem("uid")
            };
            switch ($('#priority').val()) {
                case "Must-Have":
                    formData.priority = 1;
                    break;
                case "Would be Nice to Have":
                    formData.priority = 2;
                    break;
                case "If you can":
                    formData.priority = 3;
                    break;
                default:
                    formData.priority = 0;
            }
            $.ajax({
                    type: 'POST',
                    url: '<?= site_url('api/item') ?>',
                    data: formData,
                    dataType: 'json',
                    encode: true
                })
                .done(function(response) {
                    $("#thetop").css("opacity", 1);
                    $("#thetop").removeClass("disabledbutton");
                    $(".formview").hide();
                    loadUserList();
                })
                .fail(function(data, textStatus, xhr) {
                    if (xhr == 'Unauthorized') {
                        alert("Session expired. Please Log-in again.");
                        closeView();
                        sessionStorage.clear();
                        initialize();
                    }
                });
            event.preventDefault();
        });

        // edit item
        $('#edititemformbtn').click(function(event) {

            $('.form-group').removeClass('has-error');
            $('.form-text').remove();
            var formData = {
                'title': $('#titleedit').val(),
                'price': $('#priceedit').val(),
                'itemdesc': $('#itemdescedit').val(),
                'priority': $('#priorityedit').val(),
                'url': $('#urledit').val(),
                'img': $('#imgedit').val(),
                'qty': $('#qtyedit').val(),
                'uid': sessionStorage.getItem("uid")
            };
            switch (formData.priority) {
                case "Must-Have":
                    formData.priority = 1;
                    break;
                case "Would be Nice to Have":
                    formData.priority = 2;
                    break;
                case "If you can":
                    formData.priority = 3;
                    break;
                default:
                    formData.priority = 0;
            }
            $.ajax({
                    type: 'PUT',
                    url: '<?= site_url('api/item/') ?>' + sessionStorage.getItem('itemid'),
                    data: formData,
                    dataType: 'json',
                    encode: true
                })
                .done(function(response) {
                    $("#thetop").css("opacity", 1);
                    $("#thetop").removeClass("disabledbutton");
                    $(".formview").hide();
                    loadUserList();
                })
                .fail(function(data, textStatus, xhr) {
                    if (xhr == 'Unauthorized') {
                        alert("Session expired. Please Log-in again.");
                        closeView();
                        sessionStorage.clear();
                        initialize();
                    }
                });
            event.preventDefault();
        });

        // share list
        $('#sharebtn').click(function(event) {
            $("#overlay").removeClass("disabledbutton");
            $("#thetop").css("opacity", 0.5);
            $("#overlay").css("display", "block");
            $("#thetop").addClass("disabledbutton");
            var uri = '<?= base_url() ?>' + 'index.php/App/shared/' +
                sessionStorage.getItem("lid") + '/' + sessionStorage.getItem("uid");
            var insideshare = '<table style="table-layout: fixed;width:100%;">' +
                '<tr><td VALIGN=TOP style="width:5%;"> <svg id="closeshare" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-x-circle-fill" viewBox="0 0 16 16">' +
                '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />' +
                '</svg></td>' +
                '<td style="word-wrap:break-word;"><b>Sharable link to the List: </b><br>' + uri + '</td></tr></table>';
            $("#shareview").html(insideshare);
            $("#shareview").show();

            $('#closeshare').click(function() {
                closeView();
                $("#shareview").hide();
            });

        });

        function fetchUserData(uid) {
            $.ajax({
                    type: 'GET',
                    url: '<?= site_url('api/user/') ?>' + uid,
                    dataType: 'json',
                    encode: true
                })
                .done(function(data) {
                    sessionStorage.setItem("uid", uid);
                    sessionStorage.setItem("lid", data.lid);
                    sessionStorage.setItem("username", data.userName);
                    user.attributes = data;
                    $("#loginform").hide();
                    $("#signupform").hide();
                    loadProfile();
                    loadUserList();
                })
                .fail(function(data, textStatus, xhr) {
                    if (xhr == 'Unauthorized') {
                        alert("Session expired. Please Log-in again.");
                        sessionStorage.clear();
                        initialize();
                    }
                });
        }

        function loadUserList() {
            // check if list created
            if (sessionStorage.getItem("lid")) {
                //get list details
                $.ajax({
                        type: 'GET',
                        url: '<?= site_url('api/wishlist/') ?>' + sessionStorage.getItem("lid"),
                        dataType: 'json',
                        encode: true
                    })
                    .done(function(data) {
                        list = data.list;
                        $("#overlay").addClass("disabledbutton");
                        $("#overlay").css("display", "none");
                        populateList(data);
                        $("#additembtn").show();
                        $("#sharebtn").show();
                    })
                    .fail(function(data, textStatus, xhr) {
                        if (xhr == 'Unauthorized') {
                            alert("Session expired. Please Log-in again.");
                            sessionStorage.clear();
                            initialize();
                        }
                    });
            } else {
                $("#createlistform").show();
            }
        }

        // logout
        function loadProfile() {
            $('.formview').hide();
            $('#profile').html('<span class="float-left m-5 text-dark">' + sessionStorage.getItem("username") + '<span><br>' +
                '<button id="logout" type="button" class="btn btn-outline-primary">Logout</button>');
            $('.view').show();
            $('#logout').click(function() {
                $.ajax({
                        type: 'DELETE',
                        url: '<?= site_url('api/user/') ?>' + sessionStorage.getItem("uid"),
                        dataType: 'json',
                        encode: true
                    })
                    .done(function(data) {
                        if (data.status) {
                            sessionStorage.clear();
                            initialize();
                        }
                    })
                    .fail(function(data, textStatus, xhr) {
                        if (xhr == 'Unauthorized') {
                            alert("Session expired. Please Log-in again.");
                            sessionStorage.clear();
                            initialize();
                        }
                    });
            });
        }

        $('#closeadditem').click(function() {
            closeView();
            $("#additemform").hide();
        });


        $('#additembtn').click(function() {
            $("#overlay").removeClass("disabledbutton");
            $("#thetop").css("opacity", 0.5);
            $("#overlay").css("display", "block");
            $("#thetop").addClass("disabledbutton");
            $("#additemform").show();
            var numbers = ""
            for (var i = 99; i > 0; i--) {
                numbers += '<option selected>' + i + '</option>';
            }
            $("#qty").html(numbers);
        });

        function closeView() {
            $("#thetop").removeClass("disabledbutton");
            $("#thetop").css("opacity", 1);
            $("#overlay").addClass("disabledbutton");
            $("#overlay").css("display", "none");
        }

        function populateList(listobject) {
            var insidelistview =
                '<div class="card">' +
                '<div class="card-body">' +
                '<h5 class="card-title">' + listobject.listName + '</h5>' +
                '<h6 class="card-subtitle mb-2 text-muted">' + sessionStorage.getItem("username") + '\'s List </h6>' +
                '<p class="card-text">' + listobject.listdesc + '</p>' +
                '</div></div>';
            $('#listview').html(insidelistview);
            var insideitemsview = "";
            if (listobject.items) {

                insideitemsview += '<div class="d-flex  flex-column">';
                var itemsarray = listobject.items;

                for (var i = 0; i < itemsarray.length; i++) {
                    insideitemsview += '<div id=' + itemsarray[i].itemid + ' class="p-2 ">';
                    switch (itemsarray[i].priority) {
                        case "Must-Have":
                            insideitemsview += '<div class="card  border border-danger "><table><tr><td class="item">' +
                                '<p class="card-text text-danger float-left p-2">Must-Have</p>';
                            break;
                        case "Would be Nice to Have":
                            insideitemsview += '<div class="card  border border-warning"><table><tr><td><td class="item">' +
                                '<p class="card-text text-warning float-left p-2">Would be Nice to Have</p>';
                            break;
                        case "If you can":
                            insideitemsview += '<div class="card  border border-success"><table><tr><td><td class="item">' +
                                '<p class="card-text text-success float-left p-2">If you can</p>';
                            break;
                        default:
                            insideitemsview += '<div class="card ">';
                    }
                    insideitemsview +=
                        '<div id=' + itemsarray[i].itemid + ' class=" card-body">' +
                        ' <h5>' + itemsarray[i].title + '</h5>' +
                        '<p class="card-text"><table style="width:100%"><td><b>Price: </b>' + itemsarray[i].price + '</td>' +
                        '<td><b>Quantity: </b>' + itemsarray[i].qty + '</td></table></p>' +
                        '</td><td><a  class="removebtn btn btn-danger">Remove</a></td></tr>' +
                        '</table></div></div></div>';
                }

                $('#itemsheading').html("Your wishes..");

            } else {
                $('#itemsheading').html("You haven't made any wishes..");
            }
            $('#itemsview').html(insideitemsview);
            $('.removebtn').click(function() {
                $.ajax({
                        type: 'DELETE',
                        url: '<?= site_url('api/item/') ?>' + $(this).closest('div').parent().attr('id'),
                        dataType: 'json',
                        encode: true
                    })
                    .done(function(data) {
                        if (data.status) {
                            loadUserList();
                        }
                    })
                    .fail(function(data, textStatus, xhr) {
                        if (xhr == 'Unauthorized') {
                            alert("Session expired. Please Log-in again.");
                            sessionStorage.clear();
                            initialize();
                        }
                    });
            });

            $('.item').click(function() {
                $(this).closest('div').addClass("shadow-lg");
                sessionStorage.setItem("itemid", $(this).closest('div').parent().attr('id'));
                $.ajax({
                        type: 'GET',
                        url: '<?= site_url('api/item/') ?>' + $(this).closest('div').parent().attr('id'),
                        dataType: 'json',
                        encode: true
                    })
                    .done(function(item) {
                        if (item) {
                            var insideitemview =

                                '<table style="table-layout: fixed;width: 98%;margin-left:1%;margin-right:1%;">' +
                                '<tr><td VALIGN=TOP> <svg id="closeitem" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-x-circle-fill" viewBox="0 0 16 16">' +
                                '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />' +
                                '</svg></td><td style="width: 48%;word-wrap:break-word;"><h1 class="h1 m-5 text-dark">Your Wish</h1>' +
                                '<p><span class="h4  mx-2 text-dark">Title: </span>' + item.title + '</p><p><span class="h4  mx-2 text-dark">Description: </span>' + item.itemdesc + '</p>' +
                                '<p><span class="h4  mx-2 text-dark"> <a href=' + item.url + '>URL</a></p>' +
                                '<p><span class="h4  mx-2 text-dark">Priority: </span>' + item.priority + '</p><p><span class="h4  mx-2 text-dark">Price: </span>' + item.price + '</p>' +
                                '<p><span class="h4  mx-2 text-dark">Quantity: </span>' + item.qty + '</p>' +
                                '</td><td style="width: 47%;><div style="height: 200px;width:150px;">' +
                                '<img style="max-width: 100%;max-height: 100%;display: block;" src="' + item.img + '" ></div>' +
                                '<button id="edititem" type="submit" class="btn m-5 btn-primary" style="vertical-align: bottom; display: table-cell">EDIT</button>' +
                                '</td></tr> </table>';

                            $("#thetop").css("opacity", 0.5);
                            $("#thetop").addClass("disabledbutton");
                            $("#overlay").removeClass("disabledbutton");
                            $("#overlay").css("display", "block");
                            $("#itemview").html(insideitemview);
                            $("#itemview").show();

                            $('#closeitem').click(function() {
                                closeView();
                                $("#itemview").hide();
                                loadUserList();
                            });

                            $('#closeedititem').click(function() {
                                closeView();
                                $("#edititemform").hide();
                            });

                            $('#edititem').click(function() {
                                $("#overlay").removeClass("disabledbutton");
                                $("#thetop").css("opacity", 0.5);
                                $("#overlay").css("display", "block");
                                $("#thetop").addClass("disabledbutton");

                                // load edit item form
                                $("#titleedit").val(item.title);
                                $("#imgedit").val(item.img);
                                $("#urledit").val(item.url);
                                $("#itemdescedit").val(item.itemdesc);
                                $("#priorityedit").val(item.priority);
                                $("#priceedit").val(item.price);

                                $("#edititemform").show();
                                var numbers = ""
                                for (var i = 99; i > 0; i--) {
                                    numbers += '<option selected>' + i + '</option>';
                                }
                                $("#qtyedit").html(numbers);
                                $("#qtyedit").val(item.qty);
                                $("#itemview").hide();
                            });
                        }
                    })
                    .fail(function(data, textStatus, xhr) {
                        if (xhr == 'Unauthorized') {
                            alert("Session expired. Please Log-in again.");
                            sessionStorage.clear();
                            initialize();
                        }
                    });
            });

        }

    });
</script>

</html>