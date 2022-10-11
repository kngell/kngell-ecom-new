export const colors = {
  red: "#fd397a",
  blue: "#0d6efd",
  green: "#28a745",
  yellow: "#ffc107",
  gray: "#e3eaef",
  purple: "#9833d5",
};
export const options = {
  regionStyle: {
    initial: {
      fill: colors.gray,
      "fill-opacity": 1,
      stroke: "#818181",
      "stroke-width": 0.3,
      "stroke-opacity": 1,
    },
    hover: {
      "fill-opacity": 0.5,
      cursor: "pointer",
    },
    selected: {
      fill: "#c9dfaf",
    },
    selectedHover: { fill: "#c9dfaf" },
  },
  markerStyle: {
    initial: {
      fill: "grey",
      stroke: "#505050",
      "fill-opacity": 1,
      "stroke-width": 1,
      "stroke-opacity": 1,
    },
    hover: {
      stroke: "black",
      "stroke-width": 2,
      cursor: "pointer",
    },
    selected: {
      fill: "#c9dfaf",
    },
    selectedHover: {},
  },
  gdpData: {
    US: 16.63,
    RU: 11.58,
    AU: 158.97,
    // ...
  },
  initialSelectedRegions: {
    US: colors.blue,
    RU: colors.blue,
    AU: colors.blue,
  },
  selectedCountries: ["US", "RU"],
};
