// import svgMap from "svgmap";

import * as vectorMap from "jvectormap-next/jquery-jvectormap";
import * as map from "jvectormap-content/world-mill";
import { options, colors } from "./partials/_world_map_options";

class WorldChart {
  _init = () => {
    this.vectorMap = vectorMap($);
    return this;
  };
  _show = async () => {
    const plugin = this;
    plugin._applyMap();
  };
  _applyMap = () => {
    const plugin = this;
    $.fn.vectorMap("addMap", "world_mill", map);
    $("#world_map").vectorMap({
      map: "world_mill",
      backgroundColor: "none",
      regionStyle: options.regionStyle,
      markerStyle: options.markerStyle,
      regionsSelectable: true,
      regionsSelectableOne: true,
      markersSelectable: true,
      markersSelectableOne: true,
      zoomAnimate: true,
      zoomOnScroll: false,
      // selectedRegions: options.selectedCountries,
      series: {
        regions: [
          {
            values: options.initialSelectedRegions,
            attribute: "fill",
          },
        ],
      },
      params: {
        values: options.initialSelectedRegions,
        normalizeFunction: "linear",
      },
      onRegionTipShow: function (e, el, code) {
        el.html(el.html() + " (GDP - " + options.gdpData[code] + ")");
      },
    });
  };
}
export default new WorldChart()._init();
