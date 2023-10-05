# Changelog

This file explains the latest issues and "bad practices" found in the code, and
how each of these were addressed for a better code structure and formatting.

### Issues found

-   Bad HTML markup structure.
-   Not using prepared SQL statements.
-   Copy-pasting code blocks that could be moved to a reusable function.
-   Not having a clear structure in the project.
-   Not doing optimizations for static assets.
-   Not having a source of truth for the configuration.
-   Displaying sensitive information to the user.
-   Mixing code concepts that will not work together.
-   Using a lot of "echo".
-   Depending on filenames for URL paths.
-   Not writing self-explanatory code.
-   Not having a style-guide for code formatting.
-   Use of PHP short open tags.
-   Using == operator to compare values.

### How issues were addressed

#### Bad HTML markup

The project had inconsistent HTML tags, missing basic structure parts like not including the `<html>` tag. A recommended practice and great solution is to encapsulate all of your "common" HTML structure in a template file, using PHP you can then include it whenever is necessary in your project, and if need to change something you only need to update a single file. For this a `template.php` file under `src/shared` was created, it includes simple functions that will return the HTML markup and can pass variables to edit things like the page title, adding different stylesheets, and more.

#### Not using prepared SQL statements.

You should not pass directly your variables inside your SQL statements otherwise you'll be expossed to SQL injection attacs, use [prepared SQL statements](https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php) for your SQL queries that has many additional benefits. For this issue a new `database.php` file was created, it has three principal functions, to open a connection, to make safe SQL queries, and to close the connection if needed. You can use the `safe_sql_query` function which accepts two parameters to make secure queries to your database.

#### Copy-pasting code blocks that could be moved to a reusable function.

A good practice is to follow the [DRY principle](https://en.wikipedia.org/wiki/Don%27t_repeat_yourself) which pushes you to encapsulate common logic inside functions that can be reutilized, it does make your code more readable, clean, and maintainable. For this many logic parts were encapsulated into functions inside the project files, and for authentication, a new file `session.php` was created which makes it very easy to modify the auth method if needed in the future.

#### Not having a clear structure in the project.

In this part PHP is a very open language, it does not have a "default" or recommended structure and it is mostly up to you to determinate which one best suits your needs, of course frameworks come with an opinionated structure. For this project, we've moved all the app files inside a feature-based structure, all inside `src`; this structure will allow us to easily determinate in which part a piece of code is allocated, i.e. the feature of editing our account details will be inside the `Settings` directory. Also a new file `routes.php` was created for the purpose of having a single source of truth were we can locate and edit our app routes, we should not depend on the file name for a URL path because it will make less maintainable our code and more difficult to reestructure our project or add new files, instead if we rename our `ForumTopic.php` file we just need to edit the route inside our routes function, we don't have to add any HTTP redirects to a new URL.

#### Not doing optimizations for static assets.

Static assets affect the user experience if not well optimized for the browser, as it will be downloading those large files for each user. Ideally these should be placed in a CDN service but something easy and simple that can be done on our side is to add a caching configuration for these files that will not change very often. The configuration for this can be found inside out `.htaccess` file which is the configuration for our apache server.

#### Not having a source of truth for the configuration.

Our application needs to be easy to configure and migrate whats needed, for this reason we should not have many shared settings over different files. A new `config.php` file was created and set in the project's root for an easy configuration of the database credentials and it also has settings for our global paths used in the application. This file is automatically loaded for every page so we don't need to include it on each file, if you will add a global configuration for the application like a mailing service you should define your constants inside this config.php file.

#### Displaying sensitive information to the user.

All echo's and print of SQL statements and sensitive code were removed, we shouldn't expose our backend code to users like this. If needed use a PHP debugger for this purpose of testing, if not at least remove the code when finished.

#### Mixing code concepts that will not work together.

There are many concepts that should be taken into consideration, i.e. HTTP request and headers are executed before actually printing something on screen, for this reason a piece of code found like:

```php
if ($something)
{
  echo "Error"; // remove this
  header('Location: error.php'); // you will be redirected before actually seeing the "Error" message
}
```

#### Using a lot of "echo".

Using a lot of echo's also makes your code more complex to maintain, ideally you should try not to mix the PHP code with HTML code in the same file (use templates), but an alternative is to keep them separated in the same file, instead of printing HTML code with echo's, you can differentiate concepts using statements like these:

```php
<?php if($something): ?>
  <h1>Print this</h1>
<?php endif ?>
```

#### Depending on filenames for URL paths.

As explained before, you should not depend on your filenames for the URL's of your application, as it grows it will make harder to move files or rename them without broking your application.

#### Not writing self-explanatory code.

While comments are greath for a bad code, not commenting at all is way better, code should be self-explanatory, use functions and good variable names for this `$data = safe_sql_query(...)` -> `$games = getUserGames()`

#### Not having a style-guide for code formatting.

And a final part for a great code is to maintain a consistent style across files, for this use any code formatter like prettier or PHP Intelliphense or PHPTaqwim. Tools used for this project were prettier, editorconfig, and PHPTaqwim.

#### Use of PHP short open tags
Simply not all servers have these enabled, always use `<?php ?>` instead.

#### Using == operator to compare values
Using == to check if a value is null or false can return false positives if the value is actually an empty string or 0, === checks if the values are _identical_.
