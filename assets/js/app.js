var app = app || {};
app.views = {};
app.routers = {};
app.models = {};
app.collections = {};
app.categories = {};
app.status = {};
// app.base_url = '/advanced-server-side/cw2/api/';
app.base_url = '/2017416/wishlist/api/';

function validateLoginForm() {
    var user = {
        'userName': $("input#login-username").val(),
        'password': $("input#login-password").val()
    };
    if (!user.username || !user.password) {
        return false;
    }
    return user;
}

function validateRegisterForm() {
    var user = {
        'userName': $("input#reg-username").val(),
        'password': $("input#reg-password").val(),
        'rpt_password': $("input#rpt-password").val(),
        'list_name': $("input#list-name").val(),
        'list_description': $("input#description").val(),
    };
    if (!user.username || !user.password) {
        return false;
    }
    return user;
}

function validateAddForm() {
    var item= {
        'title': $("input#title").val(),
        'description': $("input#description").val(),
        'price': $("input#price").val(),
        'url': $("input#url").val(),
        'category_id': $("select#category").val(),
        'status_id': $("select#status").val(),
        'user_id':app.user.get('id'),

    };

    return item;
}

function validateEditForm() {
    var item = {
        'title': $("input#edit-title").val(),
        'description': $("input#edit-description").val(),
        'price': $("input#edit-price").val(),
        'url': $("input#edit-url").val(),
        'category_id': $("select#edit-category").val(),
        'status_id': $("select#edit-status").val(),
        'id':$("input#id").val(),
        'user_id':app.user.get('id')
    };

    return item;
}

$(document).ready(function () {
    app.appRouter = new app.routers.AppRouter();
    $(function () {
        Backbone.history.start();
    });
});
