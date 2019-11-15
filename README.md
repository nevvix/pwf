# Nevvix PHP Web Framework (PWF)

HTML and Markdown web pages in regular directory structure powered by PHP and Apache.

## Features

- Use regular directory structure to create pages under `www` directory.
- PHP templating from `app/html/`
- Configuration in `config.json` in root directory.
- Base in `index.php` in `www` sub-directory.
- Content in `content.php` in `www` sub-directory.
- Content in `content.md` in `www` sub-directory.
- Meta tags in `.meta.json` in `www` sub-directory.
- Comes with:
    - [Bootstrap](https://getbootstrap.com) from CDN
    - [Font Awesome 4.7.0](https://fontawesome.com/v4.7.0) from CDN
    - [Google Web Fonts](https://fonts.google.com) from CDN
    - [PHP Markdown Extra](https://github.com/michelf/php-markdown)
- Localization
- Themes
- MarkdownExtra 1.8.0
- OpenGraph tags

## File and folder structure

```bash
tree -a --dirsfirst pwf
```

```

```

### bootstrap.php

`bootstrap.php` is the bootstrap file that:

- defines site-wide constants,
- loads framework libraries,
- generates the global objects `$config` and `$L10n`,
- loads global and local JSON settings.

Two global objects are created:

- `$config` object (same as `$GLOBALS['config']`) contains the site-wide configuration settings.
- `$L10n` object (same as `$GLOBALS['L10n']`) contains the translations based on selected locale.

### .htaccess

`.htaccess` contains security settings, rewrite settings, PHP development settings.

- To force HTTPS on your website, uncomment the `Rewrite...` lines in the `# Force https` section.
- To display errors while developing your site, copy `app/conf/development.htaccess` to `.htaccess`.
- To hide errors on the production server, copy `app/conf/production.htaccess` to `.htaccess`.

### favicon.ico

You can override this file and/or add `favicon.gif`.

### robots.txt

You can edit this file.

### Folders

At the project root, there are two directories:

- `app` contains framework files that are not served by Apache.
- `www` contains public files served by Apache.

### app files

- `bin` contains framework Bash scripts.
- `conf` contains Apache configuration files.
- `doc` contains framework Markdown documentation.
- `html` contains HTML/PHP layout templates.
- `json` contains site-wide JSON settings.
- `lib` contains framework PHP libraries.
- `vendor` contains third-party PHP libraries.

### www files

`www` is the public webroot that Apache serves.

- `assets` contains `css`, `img` and `js` folders.
    - `css/style.css` contains the site-wide styles, which you can override completely.
    - `img/screenshot.png` is there for the [OpenGraph](https://ogp.me) tag `og:image`, which you can override completely.
- `404` is the default "Not Found" page served by Apache when a page is not found, which you can override completely.

Under `www`, each folder corresponds directly to a URI in a browser.
Ex.: `www/about` is equivalent to `http://domain.com/about` in a browser.

You can use underscores or dashes as a character separator in folders and thus URIs.
Ex.: `www/articles/how-to-make-a-hat` is equivalent to `http://domain.com/articles/how-to-make-a-hat` in a browser.

Apache looks in a folder to serve `index.php` by default.
PWF uses `index.php` as a bootstrap code to generate the current folder's web page.

The content of a typical `index.php` in PWF is:

```php
<?php
define('DIR', __DIR__);
require_once substr_replace(__FILE__, 'bootstrap.php', strpos(__FILE__, 'www'));
```

## Create a new web page

1. Create a new directory or subdirectory under `www`.
2. Copy `app/html/index.php` and paste it in this new directory.
3. Create your content in `content.md` or `content.php` in this new directory.

The new page will automatically be available with the default layout from the templates in `app/html`.

### Create content in Markdown or HTML

- You can write content in Markdown and save it in `content.md`.
- You can write content in HTML (with or without embedded PHP) and save it in `content.php`.

### Create any type of web page

You can create any type of web page that is not following PWF convention. Examples:

- `www/services/analyst.html` is equivalent to `http://domain.com/services/analyst.html` in a browser.
- `www/services/consultant.php` is equivalent to `http://domain.com/services/consultant.php` in a browser.

- A `.php` extension implies that you are embedding PHP code in an HTML file.
- A `.html` extension implies a pure HTML markup: layout and content.

## Override the default layout

You can override any part of the default layout by creating a file with the same name in the web page path.
Ex.: you want the body section to have a different HTML markup in the About page.

- Copy `app/html/body.php` and paste it in `www/about/body.php`.
- Modify `www/about/body.php` and save.

The About page will show the layout with the current directory's body section, overriding the default template.

## Cascading lookup

PWF uses a cascading method to generate the current web page
from the default template parts and the local overriding parts of a page layout.

PWF starts with the page-specific `index.php` bootstrap.

- `index.php` loads `bootstrap.php` from the project root.
- `bootstrap.php` loads `html.php`.
- `html.php` loads `head.php` and `body.php`.
- `head.php` loads meta tags, CSS tags and JavaScript tags.
- `body.php` loads `content.php` and JavaScript tags.
- `content.php` loads either HTML/PHP content or parses the Markdown file `content.md` as HTML.

Each load above is a cascading load.  
(Locale is `en-US` as an example.)

- If `html.php` is not present in the current directory,
  PWF looks in the locale-specific directory `app/html/en-US/html.php`.
  - If `html.php` is not present in locale-specific directory,
    PWF looks in the default template directory `app/html/html.php`.
- If `head.php` is not present in the current directory,
  PWF looks in the locale-specific directory `app/html/en-US/head.php`.
  - If `head.php` is not present in locale-specific directory,
    PWF looks in the default template directory `app/html/head.php`.
- If `body.php` is not present in the current directory,
  PWF looks in the locale-specific directory `app/html/en-US/body.php`.
  - If `body.php` is not present in locale-specific directory,
    PWF looks in the default template directory `app/html/body.php`.
- If `content.php` is not present in the current directory,
  PWF looks in the locale-specific directory `app/html/en-US/content.php`.
  - If `content.php` is not present in locale-specific directory,
    PWF looks in the default template directory `app/html/content.php`.
    - If `content.md` is not present in the current directory,
      a missing file error appears in the browser so that you can create the content for this page.

### Meta data files

`bootstrap.php` also merges JSON settings files:

- Page-specific settings: `.meta.json`.
- Locale-specific settings: `app/json/en-US/config.json` and `app/json/en-US/meta.json`.
- Global settings: `app/json/config.json` and `app/json/meta.json`.

- If `.meta.json` is not present in the current directory,
  PWF looks in the locale-specific directory `app/json/en-US/meta.json`.
  - If `meta.json` is not present in locale-specific directory,
    PWF looks in the default template directory `app/json/meta.json`.

> Notice that the current directory's `.meta.json` starts with a dot while the other ones don't.  
> PWF set up Apache to hide any file that starts with a dot.  
> If a user tries to access `.meta.json` from a browser, a 404 error will display.

