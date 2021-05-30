var User = Backbone.Model.extend({
  url: "http://localhost/2017416/iWish/index.php/api/user/14",
  defaults: {
    "status": "",
    "message": "",
    "user": {}
  }
});

// var user = new User();
// user.fetch({
//   //async: false,
//   error: function (user, res) {
//     // you can pass additional options to the event you trigger here as well
//     alert(JSON.parse(res.responseText)["message"])
//     console.log(res)
//   },
//   success: function (user, res) {
//     alert(user.get("user")["USER_NAME"]);
//     console.log(user);
//   }
// });

var KermitView = Backbone.View.extend({
  el: '#kermit-view',

  initialize: function() {
    this.listenTo(this.model, 'sync change', this.render);
    this.model.fetch();
    this.render();
  },

  render: function() {
    var html = '<b>Name:</b> ' + this.model.get('name');
    html += ', occupation: ' + this.model.get('occupation');
    this.$el.html(html);
    return this;
  }
});

var kermit = new KermitModel();
var kermitView = new KermitView({model: kermit});