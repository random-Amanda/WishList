var app = app || {};

app.views.ItemEditView = Backbone.View.extend({
    el: "#editModal",
    events: {
        "click #submit-edit": "editOne",
        "click .close": "closeModal"
    },
    render: function () {
        template = _.template($('#edit-template').html());
        this.$el.html(template(this.model.attributes));
        this.$el.css('display', 'block');

    },
    editOne: function (e) {
        e.preventDefault();
        e.stopPropagation();
        var data = validateEditForm();
        if (!data) {
        } else {
            var editModel = new app.models.Item(data);
            editModel.save();
            app.list.set([editModel, editModel.attributes], {remove: false});
            app.list.comparator = 'status_id';
            app.list.sort();
            this.$el.css('display', 'none');
            app.listView.render();
        }
    },
    closeModal:
        function () {
            $("#editModal").css('display', 'none');
        }

});

app.views.ListView = Backbone.View.extend({
    el: ".container",
    initialize: function () {

    },
    events: {
        "click #btn-add": "viewModal",
        "click .close": "closeModal",
        "click #submit-add": "addOne",
        "click #btn-edit": "viewEditModal",
        "click #btn-delete": "deleteOne",
        "click #btn-share": "viewShareModal",
        "click #close-share": "closeShareModal",

    },
    render: function () {
        console.log("render");
        template_logout = _.template($('#logout-template').html());
        $("body").append(template_logout(app.user.attributes));
        template = _.template($('#list-template').html());
        this.$el.html(template(app.user.attributes));
        if(this.collection.length==0){
            $("#item_status").css('display', 'block');
        }
        this.collection.each(function (item) {
            var itemView = new app.views.ItemView({model: item});
            itemView.render(false);
        });
    },
    addOne: function (e) {
        e.preventDefault();
        e.stopPropagation();
        var data = validateAddForm();
        if (!data) {

        } else {
            var addModel = new app.models.Item(data);
            addModel.save(addModel.attributes, {
                success: function (model, response) {
                    addModel.set({id:response.id});
                    app.list.push(addModel);
                    app.list.comparator = 'status_id';
                    app.list.sort();
                    app.listView.closeModal();
                    app.listView.render();
                }
            });

        }
    },
    viewModal: function () {
        $("#myModal").css('display', 'block');
    },
    closeModal: function () {
        $("#myModal").css('display', 'none');
    },

    viewEditModal: function (e) {
        e.preventDefault();
        e.stopPropagation();
        var editModel = app.list.get(String($(e.currentTarget).attr('data-id')));
        app.editView = new app.views.ItemEditView({model: editModel});
        app.editView.render();
    },
    deleteOne: function (e) {
        var deletetModel = app.list.get(String($(e.currentTarget).attr('data-id')));
        deletetModel.destroy({"url": deletetModel.url + deletetModel.get('id')});
        app.list.remove(deletetModel);
        this.render();
    },
    viewShareModal: function () {
        $("#shareModal").css('display', 'block');
    },
    closeShareModal: function () {
        $("#shareModal").css('display', 'none');
    }
});

app.views.ShareView = Backbone.View.extend({
    el: '.container',

    render: function () {
        template = _.template($('#share-template').html());
        this.$el.html(template(app.shareUser.attributes));
        this.collection.each(function (item) {
            var itemView = new app.views.ItemView({model: item});
            itemView.render(true);
        });
    }
});