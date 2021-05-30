var app = app || {};

app.views.LoginFormView = Backbone.View.extend({
    el: ".container",

    render: function () {
        template = _.template($('#login-template').html());
        this.$el.html(template(this.model.attributes));
    },
    events: {
        "click #btn-login": "do_login",
        "click #btn-register": "do_register",
        "click .toggle": "toggle",
        "click .close": "close",
    },
    do_login: function (e) {
        e.preventDefault();
        e.stopPropagation();
        var validateForm = validateLoginForm();
        if (!validateForm) {
            console.log("validation error")
        } else {
            this.model.set(validateForm);
            var url = this.model.url + "login";
            this.model.save(this.model.attributes, {
                "url": url,
                success: function (model, reponse) {
                    localStorage.setItem('user', JSON.stringify(model));
                    app.appRouter.navigate("#", {trigger: true, replace: true});
                }
            });
        }
    },
    do_register: function (e) {
        e.preventDefault();
        e.stopPropagation();
        var validateForm = validateRegisterForm();
        if (!validateForm) {
            console.log("validation error")
        } else {
            this.model.set(validateForm);
            var url = this.model.url + "signup";
            this.model.save(this.model.attributes, {
                "url": url,
                success: function (model, response) {
                    $('.container-cust').stop().removeClass('active');
                }
            });
        }
    },
    toggle: function () {
        $('.container-cust').stop().addClass('active');
    },
    close: function () {
        $('.container-cust').stop().removeClass('active');
    }
});