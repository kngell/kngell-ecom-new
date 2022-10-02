const path = require("path");
const files = require("./filesToCopy");
class CopyFiles {
  _file = () => {
    try {
      const paths = [
        {
          source: path.resolve("./", "src", ".htaccess"),
          destination: path.resolve("./", "public", ".htaccess"),
        },
        {
          source: path.resolve("./", "src", "index.php"),
          destination: path.resolve("./", "public", "index.php"),
        },
      ];
      for (const [key, Category] of Object.entries(files)) {
        if (key == "files") {
          Category.forEach((file) => {
            paths.push({
              source: path.join("./", "src", "assets", "img") + "/" + file,
              destination:
                path.join("./", "public", "assets", "img") + "/" + file,
            });
          });
        }
        if (key == "folder") {
          Category.forEach((file) => {
            paths.push({
              source:
                path.join("./", "src", "assets", "img") + "/" + file + "/**/*",
              destination:
                path.join("./", "public", "assets", "img") + "/" + file,
            });
          });
        }
      }
      return paths;
    } catch (e) {
      console.error("We've thrown! Whoops! ", e);
    }
  };
}

module.exports = new CopyFiles()._file();
