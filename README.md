#Statamic SVG Inline Plugin

The SVG Inline plugin allows you to reference SVG files and have their contents inserted into the HTML that Statamic renders. It is functionally equivalent to `<?php include("path/to/file.svg"); ?>`. You can also have the plugin insert the SVG file as an object or image.

##Installation
1. Download or clone from github and place in the `_addons` directory.
2. Make sure the folder is named `svgi`.
3. Kick back and enjoy a delicious beverage. 

##Usage
Drop the `{{ svgi }}` tag anywhere your heart desires.

##Parameters
`{{ svgi }}` accepts the following parameters:
 - __src__: Path to the SVG file (required)
 - __as__: Optionally insert SVG as `<object>` or `<img>`
 - __alt__: Alt text (only used for `<img>`)
 
##Theme Function
__NEW!__ Link to SVGs used in your theme quicker using the `{{ svgi:theme }}` syntax. The theme function assumes a base path of the current theme when building out the URL to the SVG file. Example:
```
{{ svgi src="assets/img/logo.svg" }}
Grabs file from SITEROOT/assets/img/logo.svg
 
{{ svgi:theme src="img/logo.svg" }}
Grabs file from SITEROOT/_themes/mytheme/img/logo.svg
```
 
##TODO
 - Add [E.A.R.L.](https://github.com/raygesualdo/statamic-earl) integration