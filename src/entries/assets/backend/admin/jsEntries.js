module.exports = {
  entry: {
    //Admin General Main Js
    "js/admin/main/main": {
      import: ["js/admin/main/main"],
      dependOn: "js/librairies/adminlib",
    },
  },
};
