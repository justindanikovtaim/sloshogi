<?php

/**
 * Utilities for rendering common HTML page markup across the site.
 *
 * This file provides functions to generate HTML markup for common page elements
 * such as <html>, <head>, <link>, and <script> elements. It reduces repeatability
 * and makes it easy to update global attributes on every page.
 *
 * @file        template.php
 * @category    PHP
 * @package     Utilities
 * @license     MIT License
 */

/**
 * Generates the HTML markup for the <html> element.
 *
 * @param string $page_title The title of the page.
 * @param array $stylesheets An array of stylesheet file names.
 * @param array $scripts An array of script file names.
 * @param bool $drawBoard Indicates whether to load a board drawing function onload.
 * @return void
 */
function begin_html_page(string $page_title, array $stylesheets = [], array $scripts = [], bool $drawBoard = false)
{
  !isset($page_title) or $page_title = 'Sloshogi';
  $stylesheets = array_merge(['all_pages.css'], $stylesheets);
?>
  <!DOCTYPE html>
  <html lang="ja-JP" <?php echo ($drawBoard) ? 'onload="drawBoard()"' : ''; ?>>

  <head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo get_stylesheets($stylesheets); ?>
    <?php echo get_scripts($scripts); ?>
  </head>

  <body>
  <?php
}

/**
 * Generates the HTML markup for the <link> element.
 *
 * @param array $stylesheets An array of stylesheet file names.
 * @return string HTML markup for the <head> element.
 */
function get_stylesheets(array $stylesheets)
{
  static $static_path = '/public/css/';
  $markup = '';

  foreach ($stylesheets as $stylesheet) {
    $markup .= '<link rel="stylesheet" href="' . $static_path . $stylesheet . '">' . "\n";
  }

  return $markup;
}

/**
 * Generates the HTML markup for the <script> element.
 *
 * @param array $scripts An array of script file names.
 * @return string HTML markup for the <head> element.
 */
function get_scripts(array $scripts)
{
  static $static_path = '/public/js/';
  $markup = '';

  foreach ($scripts as $script) {
    $markup .= '<script src="' . $static_path . $script . '"></script>' . "\n";
  }

  return $markup;
}

/**
 * Generates the HTML markup for the </body> and </html> elements.
 *
 * @return void
 */
function end_html_page()
{
  ?>
  </body>

  </html>
<?php
}
