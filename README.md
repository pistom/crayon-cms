# Crayon CMS
A simple CMS, which requires no database. All data are stored in twig and json files.

Demo: [cms.crayon.pro](http://cms.crayon.pro)

## Routing
Crayon CMS uses the [altorouter](http://altorouter.com/) routing class.
### Path
#### Blog
```
| function     | variable       | example                |
|--------------|----------------|------------------------|
| articlesList | [i:page]?      | blog/[i:page]?/        |
| article      | [slug:article] | blog/[slug:article]?/` |
```
Variables in url are passed to the controller in a `$dp` array.
### Variables
Variables are divided by `|` character. They are passed to the controller in a `$sp` array.
#### SiteController
```
| function | 1st variable  | 2nd variable |
|----------|---------------|--------------|
| page     | alias of page | -            |
| contact  | menu name     | -            |
```
#### BlogController
```
| function     | 1st variable                 | 2nd variable |
|--------------|------------------------------|--------------|
| articlesList | id of category (0 for all    | menu name    |
|              | categories assigned to menu) |              |
| article      | -                            | -            |
```
## Twig
### Global variables
#### `root`
Site main directory.
#### `routes`
Array of created routes.
Examples:
Generate link: 
```html
<a href="{{ root~routes['home'].path }}">{{ routes['home'].name }}</a>
```
#### `settings`
Array of settings.
Examples:
```html
<title>{{ settings['site_name'] }}</title>
```