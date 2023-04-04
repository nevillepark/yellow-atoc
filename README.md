# Advanced Table of Contents 0.8.1

Table of contents with advanced options and customization.

## How to install an extension

[Download the ZIP file](https://github.com/nevillepark/yellow-atoc/archive/main.zip) and copy it into your `system/extensions` folder. [Learn more about extensions.](https://github.com/annaesvensson/yellow-update)

## How to make a table of contents

Create a `[atoc]` shortcut. The table of contents is automatically generated from the headings.

## Settings

The following settings can be configured in the file `system/extensions/yellow-system.ini`:

`atocLevel` = the heading levels to include in tables of contents (`1` to `6`). The default is `3`.   
`atocNumbering` = whether tables of contents should be numbered (`1`) or not (`0`).

## Styling

By editing `system/extensions/atoc.css`, you can customize the appearance of the table of contents. You can learn more about [styling lists with CSS](https://developer.mozilla.org/en-US/docs/Learn/CSS/Styling_text/Styling_lists). Your changes will not be overwritten by future updates.

## Acknowledgements

Based on [Toc](https://github.com/annaesvensson/yellow-toc/) by Anna Svensson. 

## Developer

Neville Park. [Get help](https://datenstrom.se/yellow/help/).

## To do

- [ ] [Nest lists properly](https://stackoverflow.com/questions/5899337/proper-way-to-make-html-nested-list)  
- [ ] Make it possible to specify numbered/non-numbered ToC in a post's front matter  
