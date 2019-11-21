# Nevvix PHP Website Framework (PWF)

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

- Cascading lookup
- Global cascading JSON settings

## Create a new website

### Download PWF and Markdown

Replace `~/Code/example.com` with your website project name.

```bash
cd ~/Code
git clone https://github.com/nevvix/pwf.git example.com
cd ~/Code/example.com/vendor
git clone -q -b 1.8.0 --depth=1 https://github.com/michelf/php-markdown.git
```

### Try in browser

You see the default PWF page.

<http://example.com.localhost:8000>

### Modify your templates

Based on Bootstrap by default, modify Bootstrap HTML markup in `app/html/body`.

### Modify your meta data

Modify:

- `app/json/config.json`
- `app/json/meta.json`
- `.meta.json`

Validate and prettify JSON:

<https://codebeautify.org/jsonvalidator>

### Refresh

<http://example.com.localhost:8000>

## File and folder structure

```bash
tree -a --dirsfirst pwf
```

```

```

### bootstrap.php

`app/bootstrap.php` is the bootstrap file that:

- defines site-wide constants,
- loads framework libraries,
- generates the global object `$config`,
- loads global and local JSON settings.

One global object is created:

- `$config` object (same as `$GLOBALS['config']`) contains the site-wide configuration settings.

### .htaccess

`.htaccess` contains rewrite settings, security settings, PHP development settings.

- To force HTTPS on your website, uncomment the `Rewrite...` lines in the `# Force https` section.
- To display errors while developing your site, copy `app/conf/development.htaccess` to `.htaccess`.
- To hide errors on the production server, copy `app/conf/production.htaccess` to `.htaccess`.

### favicon.ico

You can override this file and/or add `favicon.gif`.

### robots.txt

You can edit this file.

### Folders

At the project root, there are 3 directories:

- `app` contains framework files.
- `vendor` contains third-party PHP libraries.
- `www` contains public files served by Apache.

### app files and folders

- `conf` contains Apache configuration files.
- `doc` contains framework Markdown documentation.
- `html` contains HTML/PHP layout templates.
- `json` contains site-wide JSON settings.
- `lib` contains framework PHP libraries.
- `log` contains log files.
- `test` contains unit tests.
- `bootstrap.php` contains the code to load libraries and site settings.

### vendor folder

Contains third-party libraries that you can `git clone` or use [Composer](https://getcomposer.org).

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
require_once substr_replace(__FILE__, 'app/bootstrap.php', strpos(__FILE__, 'www'));
PWF::load('html.php');
```

If you want to start a session, place `session_start()` above `PWF::load()`:

```php
<?php
define('DIR', __DIR__);
require_once substr_replace(__FILE__, 'app/bootstrap.php', strpos(__FILE__, 'www'));
session_start();
PWF::load('html.php');
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

- `index.php` loads `bootstrap.php` from the `app` directory.
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

## Global cascading JSON settings

The cascading merge of JSON settings happens in `bootstrap.php`.

1. Global site-wide settings: `app/json/config.json`.
2. Global site-wide meta data: `app/json/meta.json`.
3. Page-specific meta data: `.meta.json`.

The end result is a `stdClass` object with `config.json` data
plus `meta`, `link` and `script` key=>value pairs appended at the root level.

In the `<head>` section of an HTML page, these tags will be auto-generated:

- The `meta` data is for `<meta>` and `<title>` tags.
- The `link` data is for `<link>` tags.
- The `script` data is for `<script>` tags.

The typical structure of the `$config` object will be:

```php
object(stdClass)[19]
  public 'environment' => string 'development' (length=11)
  public 'htaccess' => string 'app/conf/development.http.htaccess' (length=34)
  public 'encoding' => string 'utf-8' (length=5)
  public 'timezone' => string 'America/Los_Angeles' (length=19)
  public 'php_errors_log' => string 'app/log/php_errors.log' (length=22)
  public 'meta' => 
    object(stdClass)[2]
      public 'lang' => string 'en' (length=2)
      public 'charset' => string 'utf-8' (length=5)
      public 'robots' => 
        object(stdClass)[3]
          public 'production' => string 'index,follow' (length=12)
          public 'development' => string 'noindex,nofollow' (length=16)
      public 'viewport' => string 'width=device-width,initial-scale=1,shrink-to-fit=no' (length=51)
      public 'rights' => string 'Copyright (c) 2019 Nevvix Technology Inc.' (length=41)
      public 'sitename' => string 'Nevvix PHP Website Framework' (length=24)
      public 'title' => string 'Home' (length=4)
      public 'og:url' => string 'https://pwf.nevvix.com' (length=22)
      public 'og:image' => string 'https://pwf.nevvix.com/screenshot.png' (length=37)
      public 'og:title' => string 'Nevvix PHP Website Framework' (length=24)
      public 'og:type' => string 'website' (length=7)
      public 'og:site_name' => string 'Nevvix.com' (length=10)
      public 'og:description' => string 'HTML and Markdown web pages in regular directory structure powered by PHP and Apache.' (length=85)
  public 'link' => 
    object(stdClass)[9]
      public 'development' => 
        array (size=4)
          0 => 
            object(stdClass)[5]
              public 'rel' => string 'shortcut icon' (length=13)
              public 'href' => string '/favicon.ico' (length=12)
          1 => 
            object(stdClass)[6]
              public 'rel' => string 'stylesheet' (length=10)
              public 'href' => string 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' (length=72)
              public 'integrity' => string 'sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' (length=71)
              public 'crossorigin' => string 'anonymous' (length=9)
          2 => 
            object(stdClass)[7]
              public 'rel' => string 'stylesheet' (length=10)
              public 'href' => string '/assets/css/style.css' (length=21)
              public 'media' => string 'all' (length=3)
          3 => 
            object(stdClass)[8]
              public 'rel' => string 'stylesheet' (length=10)
              public 'href' => string 'style.css' (length=9)
              public 'media' => string 'all' (length=3)
  public 'script' => 
    object(stdClass)[13]
      public 'head' => 
        object(stdClass)[11]
          public 'production' => 
            array (size=1)
              0 => 
                object(stdClass)[10]
                  public 'src' => string '/head/section/production/from/local' (length=35)
          public 'development' => 
            array (size=1)
              0 => 
                object(stdClass)[12]
                  public 'src' => string 'script/head/global' (length=18)
      public 'body' => 
        object(stdClass)[18]
          public 'development' => 
            array (size=4)
              0 => 
                object(stdClass)[14]
                  public 'src' => string 'https://code.jquery.com/jquery-3.3.1.slim.min.js' (length=48)
                  public 'integrity' => string 'sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo' (length=71)
                  public 'crossorigin' => string 'anonymous' (length=9)
              1 => 
                object(stdClass)[15]
                  public 'src' => string 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js' (length=73)
                  public 'integrity' => string 'sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1' (length=71)
                  public 'crossorigin' => string 'anonymous' (length=9)
              2 => 
                object(stdClass)[16]
                  public 'src' => string 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' (length=70)
                  public 'integrity' => string 'sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' (length=71)
                  public 'crossorigin' => string 'anonymous' (length=9)
              3 => 
                object(stdClass)[17]
                  public 'src' => string '.meta.json/from/local' (length=21)
```

```php
stdClass Object (
    [environment] => development
    [htaccess] => app/conf/development.http.htaccess
    [encoding] => utf-8
    [timezone] => America/Los_Angeles
    [php_errors_log] => app/log/php_errors.log
    [meta] => stdClass Object (
        [lang] => en
        [charset] => utf-8
        [robots] => stdClass Object (
            [production] => index,follow
            [development] => noindex,nofollow
        )
        [viewport] => width=device-width,initial-scale=1,shrink-to-fit=no
        [rights] => Copyright (c) 2019 Nevvix Technology Inc.
        [sitename] => Nevvix PHP Website Framework
        [title] => Home
        [og:url] => https://pwf.nevvix.com
        [og:image] => https://pwf.nevvix.com/screenshot.png
        [og:title] => Nevvix PHP Website Framework
        [og:type] => website
        [og:site_name] => Nevvix.com
        [og:description] => HTML and Markdown web pages in regular directory structure powered by PHP and Apache.
    )
    [link] => stdClass Object (
        [development] => Array (
            [0] => stdClass Object (
                [rel] => shortcut icon
                [href] => /favicon.ico
            )
            [1] => stdClass Object (
                [rel] => stylesheet
                [href] => https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css
                [integrity] => sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T
                [crossorigin] => anonymous
            )
            [2] => stdClass Object (
                [rel] => stylesheet
                [href] => /assets/css/style.css
                [media] => all
            )
            [3] => stdClass Object (
                [rel] => stylesheet
                [href] => style.css
                [media] => all
            )
        )
    )
    [script] => stdClass Object (
        [head] => stdClass Object (
            [production] => Array (
                [0] => stdClass Object (
                    [src] => /head/section/production/from/local
                )
            )
            [development] => Array (
                [0] => stdClass Object (
                    [src] => script/head/global
                )
            )
        )
        [body] => stdClass Object (
            [development] => Array (
                [0] => stdClass Object (
                        [src] => https://code.jquery.com/jquery-3.3.1.slim.min.js
                        [integrity] => sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo
                        [crossorigin] => anonymous
                    )
                [1] => stdClass Object (
                        [src] => https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js
                        [integrity] => sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1
                        [crossorigin] => anonymous
                    )
                [2] => stdClass Object (
                        [src] => https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js
                        [integrity] => sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM
                        [crossorigin] => anonymous
                    )
                [3] => stdClass Object (
                        [src] => .meta.json/from/local
                    )
            )
        )
    )
)
```