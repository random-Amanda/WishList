<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- load Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <style>
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
    </div>
    <div id="thetop">
        <table style="width: 80%;margin-left:1%;margin-right:10%;margin-top:10%;">
            <tr>
                <td style="width: 30%;" VALIGN=TOP>
                    <!-- list view -->
                    <div id="listview" class=" view mx-5  lead text-dark stick-top">
                    </div><br>
                </td>
                <td style="width: 65%;text-align:center;">
                    <h5 id="itemsheading" class=" view"></h5>
                    <!-- items in list view  -->
                    <div id="itemsview" class="my-5 rounded-circle view mx-auto lead text-dark ">
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
<script>
    $(document).ready(function() {
        loadUserList();

        function loadUserList() {
            sessionStorage.setItem('uid', <?= $uid ?>);
            sessionStorage.setItem('lid', <?= $lid ?>);
            $.ajax({
                    type: 'GET',
                    url: '<?= site_url('api/wishlist/view/') ?>' + sessionStorage.getItem("lid") + '/' + sessionStorage.getItem("uid"),
                    dataType: 'json',
                    encode: true
                })
                .done(function(data) {
                    $.ajax({
                            type: 'GET',
                            url: '<?= site_url('api/user/view/') ?>' + sessionStorage.getItem("uid"),
                            dataType: 'json',
                            encode: true
                        })
                        .done(function(response) {
                            sessionStorage.setItem('username',response.userName);
                            $("#overlay").addClass("disabledbutton");
                            $("#overlay").css("display", "none");
                            populateList(data);
                        })
                        .fail(function(response, textStatus, xhr) {
                            $('#itemsheading').html('<div class="alert alert-danger" role="alert">' +
                                'Could not find User' +
                                ' </div>');
                        });
                })
                .fail(function(data, textStatus, xhr) {
                    $('#itemsheading').html('<div class="alert alert-danger" role="alert">' +
                        'Invalid Link' +
                        ' </div>');
                });
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
                        '</td></tr>' +
                        '</table></div></div></div>';
                }

                $('#itemsheading').html(sessionStorage.getItem("username")+"\'s wishes..");

            } else {
                $('#itemsheading').html(sessionStorage.getItem("username")+" hasn't made any wishes..");
            }
            $('#itemsview').html(insideitemsview);
            $('.item').click(function() {
                sessionStorage.setItem("itemid", $(this).closest('div').parent().attr('id'));
                $.ajax({
                        type: 'GET',
                        url: '<?= site_url('api/item/view/') ?>' + $(this).closest('div').parent().attr('id') + '/' + sessionStorage.getItem("uid"),
                        dataType: 'json',
                        encode: true
                    })
                    .done(function(item) {
                        if (item) {
                            var insideitemview =

                                '<table style="table-layout: fixed;width: 98%;margin-left:1%;margin-right:1%;">' +
                                '<tr><td VALIGN=TOP> <svg id="closeitem" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-x-circle-fill" viewBox="0 0 16 16">' +
                                '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />' +
                                '</svg></td><td style="width: 48%;word-wrap:break-word;"><h1 class="h1 m-5 text-dark">Wish</h1>' +
                                '<p><span class="h4  mx-2 text-dark">Title: </span>' + item.title + '</p><p><span class="h4  mx-2 text-dark">Description: </span>' + item.itemdesc + '</p>' +
                                '<p><span class="h4  mx-2 text-dark"> <a href=' + item.url + '>URL</a></p>' +
                                '<p><span class="h4  mx-2 text-dark">Priority: </span>' + item.priority + '</p><p><span class="h4  mx-2 text-dark">Price: </span>' + item.price + '</p>' +
                                '<p><span class="h4  mx-2 text-dark">Quantity: </span>' + item.qty + '</p>' +
                                '</td><td style="width: 47%;><div style="height: 200px;width:150px;">' +
                                '<img style="max-width: 100%;max-height: 100%;display: block;" src="' + item.img + '" ></div>' +
                                '</td></tr> </table>';

                            $("#thetop").css("opacity", 0.5);
                            $("#thetop").addClass("disabledbutton");
                            $("#overlay").removeClass("disabledbutton");
                            $("#overlay").css("display", "block");
                            $("#itemview").html(insideitemview);
                            $("#itemview").show();

                            $('#closeitem').click(function() {
                                $("#thetop").css("opacity", 1);
                                $("#thetop").removeClass("disabledbutton");
                                $("#overlay").addClass("disabledbutton");
                                $("#overlay").css("display", "none");
                                $("#itemview").hide();
                                loadUserList();
                            });
                        }
                    })
                    .fail(function(data, textStatus, xhr) {
                        console.log('krgj');
                    });
            });

        }
    });
</script>