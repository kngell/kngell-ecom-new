module.exports = {
  entry: {
    //Admin General Main Js
    "js/admin/main/main": {
      import: ["js/admin/main/main"],
      dependOn: "js/librairies/adminlib",
    },
    "js/admin/pages/home/home": {
      import: ["js/admin/pages/home/home_page"],
      dependOn: "js/librairies/adminlib",
    },
    "js/admin/pages/products/products": {
      import: ["js/admin/pages/products/products"],
      dependOn: "js/librairies/adminlib",
    },
  },
};
