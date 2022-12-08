# FEUERWASSER MoveItem (frwssr_moveitem)
Field type to move an item from a multi-item region to another one in [PerchCMS](https://grabaperch.com/).

## Installation

1. Download zip archive and extract locally.
2. Create a `frwssr_moveitem` folder in the `/perch/addons/fieldtypes/` folder of your perch install.
3. Copy the files `frwssr_moveitem.class.php`, `index.php`, and `init.js` to the `/perch/addons/fieldtypes/frwssr_moveitem` folder.

## Usage
In a perch template, you can use this field type as simple as follows:
```html
<perch:content id="move" type="frwssr_moveitem" moveto="123|news,456|archive" suppress>
```

### Attributes
- *moveto* - Provide the regions, the item may be moved to. Failing to do so will render an alert in Perch Admin. Also give a readable label for each region—separated from its ID with the pipe character (`|`), as you might be familiar with from Perch’s `select` field options. The order has to be `ID|label`.
Pattern: `123|news,456|archive`.  
Example: `moveto="123|news,456|archive"`.
- *hint* - Customize the text on the first—otherwise empty—list item. Defaults to “↗️ Move item ⚠️” (—the emoji trying to signify the *danger zone* character of the field.)
- *styles* - Customize the styles of the field with CSS. Defaults to `background-color: slategray`. You may get fancy with something like `styles="background-color: teal; background-image: linear-gradient(to top right, teal, tomato); border-radius: 10px 0 10px 10px; border: 2px dashed tomato"`, too. Impress your Perch users!
- *unsetfields* - Pass the IDs of one or more fields to be unset—and the (optional) desired unset values—to have them unset/altered. If no value is provided, the field will be set to an empty string.  
Be aware, that commas (`,`) and the pipe character (`|`) cannot be part of an unset value. You may use encoded HTML characters, though need to have the ` html` attribute on the outputting field for it to render as desired.   
Pattern: `id|,id|unset value`.  
Example: `unsetfields="slug,date,islive|❌"`.

### Example
```html
<perch:content id="move" type="frwssr_moveitem" label="Move this awesome item" hint="Select target region" moveto="123|news,456|archive" hint="Select target region" styles="linear-gradient(to top right, teal, tomato)" unsetfields="slug,date,islive|❌" help="Beware: The move will be executed ON CHANGE of this field." suppress>
```

### Notes
- Make sure, the target regions match the template of the original region—and, that those are multi-item regions, too! 
- Always include the original region in the *moveto* options for better usability/error correction. The original region will be displayed `disabled` in the drop-down list.
- The target region may live in another page.
- Always use `suppress` on the `frwssr_moveitem` field to make sure it doesn’t show up in your website (if the same template is used to render the content, that is).
- This fieldtype was developed under Perch Runway Version 4.5 on a server running PHP 8.0.x.  
**Use at own risk!**

### Acknowledgement
I want to thank fellow Percher [Hussein Al Hammad :whale:](https://hussein-alhammad.com/) for pointing me in the right direction on troubleshooting PHP Exceptions.


# License
This project is free, open source, and GPL friendly. You can use it for commercial projects, for open source projects, or for almost whatever you want, really.

# Donations
This is free software, but it took some time to develop. If you use it, please let me know—I live off of positive feedback…and chocolate.
If you appreciate the fieldtype and use it regularly, feel free to [buy me some sweets](https://paypal.me/nlsmlk).

# Issues
Create a GitHub Issue: https://github.com/frwssr/frwssr_moveitem/issues or better yet become a contributor.

Developer: Nils Mielke (nils.m@feuerwasser.de, [@nilsmielke on Mastodon](https://det.social/@nilsmielke)) of [FEUERWASSER](https://www.feuerwasser.de)
