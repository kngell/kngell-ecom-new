module.exports = {
  entry: {
    //Admin General Main sass
    "css/admin/main/main": {
      import: ["css/admin/main/_index.sass"],
      dependOn: "css/librairies/adminlib",
    },
  },
};
