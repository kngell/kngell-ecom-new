module.exports = {
  entry: {
    //Main Js
    "js/client/main/main": {
      import: ["js/client/main/main"],
      dependOn: "js/librairies/frontlib",
    },
    "js/plugins/homeplugins": {
      import: ["js/plugins/homeplugins"],
      dependOn: "js/librairies/frontlib",
    },
    // Checkout
    "js/client/components/checkout/checkout": {
      import: ["js/client/components/checkout/checkout"],
      dependOn: "js/librairies/frontlib",
    },
    // Todo
    "js/client/components/todoList/todoList": {
      import: ["js/client/components/todoList/todoList"],
      dependOn: "js/librairies/frontlib",
    },
    "js/client/main/open_login_modal": {
      import: ["js/client/main/partials/_open_login_modal"],
      dependOn: "js/librairies/frontlib",
    },
    //visitor Home page
    "js/client/brand/phones/home/home": {
      import: ["js/client/brand/phones/home/index"],
      dependOn: "js/librairies/frontlib",
    },
  },
};
