const fs = require("fs");
const YAML = require("yaml");

module.export = () => {
  const file = fs.readFileSync("root/Config/Yaml/select2Field.yml", "utf8");
  console.log(file);
};
