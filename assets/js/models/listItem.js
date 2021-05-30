var app = app || {};

app.models.Item = Backbone.Model.extend({
    urlRoot: app.base_url + 'item/',
    defaults: {
        id: null,
        price: null,
        url: null,
        category_id: null,
        status_id: null,
        title: null,
        description: null
    },
    url: app.base_url + 'item/',

});

app.collections.ItemCollection = Backbone.Collection.extend({
    model: app.models.Item,
    url: app.base_url + 'item/'
});

app.models.Category = Backbone.Model.extend({
    urlRoot: app.base_url + 'category/',
    defaults: {
        id: null,
        name:null
    },
    url: app.base_url + 'category/',
});

app.collections.CategoryCollection = Backbone.Collection.extend({
    model: app.models.Category,
    url: app.base_url+'category/'
});

app.models.Status = Backbone.Model.extend({
    urlRoot: app.base_url+'status/',
    defaults: {
        id: null,
        status: null
    },
    url: app.base_url+'status/',
});

app.collections.Satus= Backbone.Collection.extend({
    model: app.models.Status,
    url: app.base_url+'status/'
});
