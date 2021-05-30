var app = app || {};

app.models.User = Backbone.Model.extend({
    defaults: {
        username: "",
        password: "",
        id: null,
        list_name: "",
        list_description: ""
    },
    url: app.base_url + 'user/',

});