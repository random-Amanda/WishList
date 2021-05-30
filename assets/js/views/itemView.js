var app = app || {};

app.views.ItemView = Backbone.View.extend({

    el: "#item",

    render: function (is_share) {

        template = _.template($('#item-template').html());
        this.$el.append(template(this.model.attributes));
        if (is_share) {
            $(".fa-edit").hide();
            $(".fa-trash").hide();
        }
    }
});

app.views.CategoryView = Backbone.View.extend({
    el: "#category",
    initialize: function () {
        this.fetch();
    },
    render: function () {
        this.collection.each(function (cat) {
            template = _.template($('#cat-template').html());
            this.$el.append(template(cat.attributes));
        });

    }
});

app.views.StatusView = Backbone.View.extend({
    el: "#status",
    initialize: function () {
        this.fetch();
    },
    render: function () {
        template = _.template($('#status-template').html());
        this.$el.append(template(this.model.attributes));
    }
});