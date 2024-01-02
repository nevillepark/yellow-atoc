# Advanced Table of Contents 0.8.2

Generate a nested table of contents from page headings.

* [How to install an extension](#how-to-install-an-extension)  
* [Settings](#settings)  
* [Styling](#styling)  
* [How to make a table of contents](#how-to-make-a-table-of-contents) 
  * [Best practices](#best-practices)  
   - [Markdown](#markdown)  
   - [Uniqueness](#uniqueness)  
   - [Nested & sequential](#nested-sequential)  
* [Acknowledgements](#acknowledgements)  
* [Developer](#developer)  
* [To do](#to-do)

## How to install an extension

[Download the ZIP file](https://github.com/nevillepark/yellow-atoc/archive/main.zip), copy it into your `system/extensions` folder, and run `yellow.php install`. [Learn more about extensions.](https://github.com/annaesvensson/yellow-update)

## Settings

The following settings can be configured in the file `system/extensions/yellow-system.ini`:

- `atocLevel` = the levels of headings to include in tables of contents (`2` to `6`). The default is `4`.  
- `atocNumbering` = whether tables of contents should be numbered (`1`) or not (`0`).

## Styling

By editing `system/extensions/atoc.css`, you can customize the appearance of the table of contents. You can learn more about [styling lists with CSS](https://developer.mozilla.org/en-US/docs/Learn/CSS/Styling_text/Styling_lists). Your changes will not be overwritten by future updates.

## How to make a table of contents

When editing a page, enter `[atoc]` where the table of contents should appear. The table of contents will be automatically generated from the headings.

### Best practices

For best results, your headings should be 1) in Markdown format, 2) unique, 3) properly nested, and 4) sequential. Here is [more information about properly using headings](https://www.a11yproject.com/posts/how-to-accessible-heading-structure/#best-practices-summarized). 

#### Markdown

Headings should be in Markdown format, e. g. `## Introduction` instead of `<h2>Introduction</h2>`. If you do want to use HTML headings, you must manually add an anchor ID, e. g. `<h2 id="introduction">Introduction</h2>`.

Level 1 headings (`#`, `<h1>`) should be reserved for the website title. If you still want to use them, you will have to enter them in HTML with anchor IDs, and the table of contents will not look as nice. 

#### Uniqueness

If you have multiple headings with the same title (e. g., multiple sections with the heading "Instructions"), only the first one will appear in the table of contents. This applies even if the headings are different levels. To work around this, either make sure each heading is different, or write it in HTML and add a unique anchor ID. E. g., this will work:

```
### Instructions  
  
[…]  
  
<h3 id="instructions-2">Instructions</h3>  
```

#### Nested & sequential

Headings should be **nested**: a section with a level 2 heading (`##`, `<h2>`) should be divided into sections with level 3 headings (`###`, `<h3>`), not the other way around. Headings should also be **sequential**: you should not skip heading levels, e. g. going from a level 2 heading to a level 4 heading. 

If you don't do this, your table of contents will have gaps in it, and if it is a numbered table of contents the numbering will be off. However, it should still be usable. 

## Acknowledgements

Based on [Toc](https://github.com/annaesvensson/yellow-toc/) by Anna Svensson. Many thanks to everyone on the Fediverse who helped me with the code, especially [Phire](https://phire.place/@phire). 

## Developer

Neville Park. [Get help](https://datenstrom.se/yellow/help/).

## To do

- [x] [Nest lists properly](https://stackoverflow.com/questions/5899337/proper-way-to-make-html-nested-list)  
- [ ] Make it possible to specify numbered/non-numbered ToC in the shortcode/front matter  
- [ ] Fix headings with the same titles as previous headings not appearing
