# Crayon CMS
A simple CMS, which requires no database. All data are stored in twig and json files.

Demo: [cms.crayon.pro](http://cms.crayon.pro)

## Installation
1. Clone or download the CMS source.
2. Run composer to download dependencies.
3. Go to Back Office (`youdomain/_bo`) and change admin password.

## Routing
Crayon CMS uses the [altorouter](http://altorouter.com/) routing class.
### Path
#### Blog
```
| function     | variable       | example                |
|--------------|----------------|------------------------|
| articlesList | [i:page]?      | blog/[i:page]?/        |
| article      | [slug:article] | blog/[slug:article]?/  |
```
Variables of the path are passed to the controller in the `$dp` array.
### Variables
Variables are divided by `|` character. They are passed to the controller in the `$sp` array.
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
Array with created routes.

**Examples:**

Generate link: 
```html
<a href="{{ root~routes['home'].path }}">{{ routes['home'].name }}</a>
```
#### `settings`
Array with site settings.

**Examples:**

```html
<title>{{ settings['site_name'] }}</title>
```
## Templates
You can create your own twig templates by overloading the base template.
Place your files in `/views/_templates/{name of template}` directory.
The directory structure should be the same as the main view directory.
Example:
```
/views/_templates/new_template/base.html.twig
/views/_templates/new_template/blog/article.html.twig
```
The folder name with the new template appears in the settings.