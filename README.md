# Footnotes Made Easy

Footnotes Made Easy is a simple, but powerful, method of adding footnotes into your posts and pages.

## Key features include…

* Simple footnote insertion via double parentheses
* Combine identical notes
* Solution for paginated posts
* Suppress Footnotes on specific page types
* Option to display ‘pretty’ tooltips using jQuery
* Lots of configuration options

And much, much more!

> Footnotes Made Easy is a fork of [WP Footnotes](https://github.com/drzax/wp-footnotes), a plugin by **Simon Elvery** which was abandoned some years ago.

## Installation & Usage

Installation is best handled from within the WordPress admin area, but you can [download the latest release from the WordPress Plugin Directory](https://wordpress.org/plugins/footnotes-made-easy/).

[Documentation on using Footnotes Made Easy](https://divibanks.io/wordpress-plugins/footnotes-made-easy/) is at the [author's website](https://divibanks.io). Further support is crowd-sourced via the [WordPress support forums](https://wordpress.org/support/plugin/footnotes-made-easy/).

### Getting Started

Creating a footnote is incredibly simple – you just need to include your footnote in double parentheses, such as this…

This is a sentence ((and this is your footnote)).

The footnote will then appear at the bottom of your post/page.

Important note: Make sure you include a space before your opening double parentheses or the footnote won’t work!

### Options

You have a fair few options on how the identifier links, footnotes and back-links look which can be found in the WordPress admin area under **Settings -> Footnotes**.

### Paginated Posts

Some of you seem to like paginating post, which is kind of problematic. By default, each page of your post will have its own set of footnotes at the bottom and the numbering will start again from 1 for each page.

The only way to get around this is to know how many posts are on each page and tell Footnotes Made Easy what number you want the list to start at for each of the pages. So at some point on each page `(that is, between each <!--nextpage--> tag)` you need to add a tag to let the plugin know what number the footnotes on this page should start at. The tag should look like this `<!--startnum=5-->` where “5” is the number you want the footnotes for this page to start at.

### Referencing

Sometimes it’s useful to be able to refer to a previous footnote a second (or third, or fourth…) time. To do this, you can either simply insert the exact same text as you did the first time and the identifier should simply reference the previous note. 

Alternatively, if you don’t want to do all that typing again, you can construct a footnote like this: `((ref:1))` and the identifier will reference the footnote with the given number.

Even though it’s a little more typing, using the exact text method is much more robust. The number referencing will not work across multiple pages in a paged post (but will work within the page). 

Also, if you use the number referencing system you risk them identifying the incorrect footnote if you go back and insert a new footnote and forget to change the referenced number.

## Bug Reports

Bug reports are best made via the plugin’s Github Issues tracker page.

## Maintainers

- [Patrick Lumumba](https://github.com/lumumbapl)

## Contributors

- [David Artiss](https://github.com/dartiss)
- [Emmanuel Milimo](https://github.com/manuell18)
- [Chris Ocen](https://github.com/chrisjrocen)

Contributions are welcome. [Please review the guidelines for contributing to Footnotes Made Easy](https://github.com/divibanks/footnotes-made-easy/blob/main/CONTRIBUTING.md) before submitting a pull request on GitHub.
