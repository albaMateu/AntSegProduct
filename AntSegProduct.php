<?php

/**
 * @package AntSegProduct
 */
/*
Plugin Name: AntSegProduct Anterior y siguiente
Plugin URI: 
Description: Muestra enlace con imagen y titulo del producto anterior y siguiente en la ficha del producto.
Version: 1.0
Author: Alba Mateu
Author URI: https://almape.dev
Text Domain: antsegproduct
*/

/*  evitar que un usuario pueda ejecutar código PHP insertando la ruta en la barra del navegador */
defined('ABSPATH') or die("Bye bye");
/* ruta del plugin */
define('AS_RUTA', plugin_dir_path(__FILE__));


include(AS_RUTA . 'includes/functions.php');

/* activar el plugin */
add_action('init', 'init_antsegproduct');
add_action('plugins_loaded', 'load_antsegproduct');

function init_antsegproduct()
{
    load_plugin_textdomain("antsegproduct", false,  dirname(plugin_basename(__FILE__)) . '/i18n');
}

function load_antsegproduct()
{
    /* si no existeix la classe woocommerce no executes la funcio */
    if (!class_exists('WooCommerce'))
        exit;

    /* Enganxar en woocommerce per a que s'execute */
    add_action('woocommerce_before_single_product', 'AntSegProduct');
    add_action('woocommerce_after_single_product', 'AntSegProduct');
}

/* añadimos el css */
function antsegproduct_styles()
{
    wp_enqueue_style('antsegproduct',  plugin_dir_url(__FILE__) . '/public/css/styles.css');
}
add_action('wp_enqueue_scripts', 'antsegproduct_styles');

/* añadimos el js */
function antsegproduct_js()
{
    wp_enqueue_script('antsegproduct',  plugin_dir_url(__FILE__) . '/public/scripts/main.js', array('jquery'));
}
add_action('wp_enqueue_scripts', 'antsegproduct_js');
