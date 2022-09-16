module.exports = {
  entry: {
    //Admin General Main Js
    "js/admin/main": {
      import: ["js/admin/main/main"],
      dependOn: "css/librairies/adminlib",
    },
  },
};
