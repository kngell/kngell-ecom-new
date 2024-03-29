var Isotope = require("isotope-layout");
const responsive = {
  0: {
    items: 1,
  },
  600: {
    items: 3,
  },
  1000: {
    items: 5,
  },
};
import owlCarousel from "owl.carousel";
// import comments from "corejs/comments";
import imageLoaded from "corejs/waitfor";
import banner1 from "img/banner1-cr-500x150.jpg";
import banner2 from "img/banner2-cr-500x150.jpg";
import blog1 from "img/blog/blog1.jpg";
import blog2 from "img/blog/blog2.jpg";
import blog3 from "img/blog/blog3.jpg";
import logo1 from "img/logo1.png";
import logo2 from "img/logo2.png";
import payment from "img/payment.png";
class Carousel {
  constructor(element) {
    this.element = element;
  }
  _init = () => {
    this._setupVariables();
    this._setupEvents();
  };
  _setupVariables = () => {
    this.banner = this.element.find("#banner-area");
    this.topSale = this.element.find("#top-sale");
    this.specialPrice = this.element.find("#special-price");
    this.newPhone = this.element.find("#new-phones");
    this.blog = this.element.find("#blog");
  };
  _setupEvents = () => {
    var phpPlugin = this;
    //=======================================================================
    //Owl carousel
    //=======================================================================
    phpPlugin.banner.find(".owl-carousel").owlCarousel({
      dots: true,
      items: 1,
      autoplay: true,
      checkVisible: false,
    });

    //Top sales
    phpPlugin.topSale.find(".owl-carousel").owlCarousel({
      loop: true,
      nav: true,
      dots: false,
      margin: 15,
      responsive: responsive,
      checkVisible: false,
    });

    //new product
    phpPlugin.newPhone.find(".owl-carousel").owlCarousel({
      loop: true,
      nav: false,
      dots: true,
      margin: 15,
      responsive: responsive,
      checkVisible: false,
    });
    //blog
    phpPlugin.blog.find(".owl-carousel").owlCarousel({
      loop: true,
      nav: false,
      dots: true,
      responsive: {
        0: { items: 1 },
        600: { items: 3 },
      },
      checkVisible: false,
      center: true,
    });

    function equal_height() {
      var maxHeight = 0;
      phpPlugin.specialPrice.find(".grid-item").each(function () {
        var thisH = $(this).height();
        if (thisH > maxHeight) {
          maxHeight = thisH;
        }
      });

      phpPlugin.specialPrice.find(".grid-item").height(maxHeight);
    }
    /**
     * Special Price
     * ====================================================
     */
    (async () => {
      return new Promise((resolve, reject) => {
        var gridIMG = phpPlugin.specialPrice.find(".grid .grid-item img");
        var elem = document.querySelector(".grid");
        if (elem !== null) {
          imageLoaded(gridIMG)
            .then(() => {
              resolve(
                new Isotope(elem, {
                  itemSelector: ".grid-item",
                  layoutMode: "masonry",
                  // sortBy: "name",
                  // filter: ".alkali, .alkaline-earth",
                  masonry: {
                    columnWidth: 0,
                    isFitWidth: true,
                    rowHeight: 150,
                    gap: 10,
                  },
                })
              );
            })
            .catch(() => console.log("error image"));
        }
      });
    })()
      .then((mod) => {
        phpPlugin.specialPrice
          .find(".button-group")
          .on("click", "button", function (e) {
            e.preventDefault();
            var filterValue = $(this).attr("data-filter");
            mod.arrange({
              filter: filterValue,
            });
          });
        equal_height();
      })
      .catch((e) => console.log(e));
  };
}

export default new Carousel($("#main-site"));
