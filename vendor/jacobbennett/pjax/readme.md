# PJAX for Laravel 5.*
[![Latest Version on Packagist](https://img.shields.io/packagist/v/jacobbennett/pjax.svg?style=flat-square)](https://packagist.org/packages/jacobbennett/pjax)
[![Total Downloads](https://img.shields.io/packagist/dt/jacobbennett/pjax.svg?style=flat-square)](https://packagist.org/packages/jacobbennett/pjax)

Enable the use of PJAX in Laravel 5.*.

#### Installation

Add `jacobbennett/pjax` to `require` section in your `composer.json`

	"jacobbennett/pjax": "~1.0"

Add `'JacobBennett\Pjax\PjaxMiddleware',` to `$middleware` in `app/Http/Kernel.php`

#### How to use

This middleware will check, before outputting the http response, for the `X-PJAX`'s 
header in the request. If found, it will crawl the response to return the requested 
element defined by `X-PJAX-Container`'s header.


jQuery PJAX JS is required to use this package [jquery.pjax.js](https://github.com/defunkt/jquery-pjax).

See an example on [Laracasts](https://laracasts.com/lessons/faster-page-loads-with-pjax)

### Notes:

Sometimes when using PJAX it will timeout and trigger a standard page reload. This could be due to various factors but one thing you may try is to extend the default timeout for PJAX using this little snippet when you initialize PJAX.

```js

$(document).ready(function(){

    // does current browser support PJAX
    if ($.support.pjax) {
    	$.pjax.defaults.timeout = 1000; // time in milliseconds
    }
    
});

```
