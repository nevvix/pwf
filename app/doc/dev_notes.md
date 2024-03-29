# Development notes

## GitHub

Make a new repository with a MIT license.

## Initialize

In Vagrant terminal:

```bash
cd ~/Web
git clone https://github.com/nevvix/pwf.git
cd ~/Web/pwf
touch README.md
mkdir -p {app/{conf,doc,html,json,lib,log.test},www/assets/{css,img,js},vendor}
tree -a -I ".git" --dirsfirst > app/doc/tree.txt
```

## Download PHP Markdown Extra

```bash
cd ~/Web/pwf
git clone -q -b 1.8.0 --depth=1 https://github.com/michelf/php-markdown.git vendor/php-markdown
```

## Download .htaccess

```bash
cd ~/Web/pwf
wget https://raw.githubusercontent.com/h5bp/server-configs-apache/master/dist/.htaccess
```

---

```
@import url("//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
@import url("//fonts.googleapis.com/css?family=Roboto:300,400,700");
@import url("//fonts.googleapis.com/css?family=Libre+Franklin:300,400,700");


//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css
//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js

https://pages.github.com

https://jekyllrb.com/docs


https://getbootstrap.com/docs/4.3/getting-started/introduction/

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
    <h1>Hello, world!</h1>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>



https://github.com/jwplayer/jwplayer
https://developer.jwplayer.com/jwplayer/docs
```
