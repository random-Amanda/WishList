var app = app || {};

app.routers.AppRouter = Backbone.Router.extend({
    routes: {
        "": "viewList",
        "login": "login",
        "logout": "logout",
        "share/:id": "viewShare"

    },

    login: function () {
        userJson = JSON.parse(localStorage.getItem("user"));
        if (userJson == null) {
            app.user = new app.models.User(userJson);

            if (!app.loginView) {
                app.loginView = new app.views.LoginFormView({model: app.user});
                app.loginView.render();
            }
        } else {
            this.viewList();
        }
    },

    viewList: function () {
        userJson = JSON.parse(localStorage.getItem("user"));
        if (userJson != null) {
            app.user = new app.models.User(userJson);
            app.list = new app.collections.ItemCollection();
            app.listView = new app.views.ListView({collection: app.list});
            var url = app.listView.collection.url + app.user.get("id");
            app.list.fetch({
                "url": url,
                wait: true,
                success: function (collection, response) {
                    app.listView.render();
                },
                error: function (model, xhr, options) {
                    if (xhr.status == 404) {
                        app.listView.render();
                        $("#item_status").css('display', 'block');
                    }
                }
            });

        } else {
            app.appRouter.navigate("#login", {trigger: true, replace: true});
        }
    },
    logout: function () {
        $("#navbar").remove();
        localStorage.clear();
        window.location.href = "";
    },

    viewShare: function (id) {
        if (!app.shareView) {
            app.shareUser = new app.models.User();
            app.shareUser.fetch({"url": app.shareUser.url + id});
            app.shareView = new app.views.ShareView({collection: new app.collections.ItemCollection()});
            var url = app.shareView.collection.url + id;
            app.shareView.collection.fetch({
                "url": url,
                wait: true,
                success: function (collection, response) {
                    app.shareView.render();
                },
                error:function (model,xhr) {
                    if(xhr.status==404){
                        $("#item_status").css('display', 'block');
                    }
                }
            });

        } else {
            this.viewList();
        }
    }

})
;
