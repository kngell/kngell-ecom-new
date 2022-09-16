module.exports = {
  entry: {
    //Front General Main sass
    "css/client/main": {
      import: ["css/client/main/main.sass"],
      dependOn: "css/librairies/frontlib",
    },
    //Home plugins
    "css/plugins/homeplugins": {
      import: ["css/plugins/homeplugins.sass"],
      dependOn: "css/librairies/frontlib",
    },
    // Learning
    "css/client/learn/learn": {
      import: ["css/client/learn/learn.sass"],
      dependOn: "css/librairies/frontlib",
    },
    // Email Main css
    "css/client/components/email/main": {
      import: ["css/client/components/email/_index.sass"],
      dependOn: "css/librairies/frontlib",
    },
  },
};
