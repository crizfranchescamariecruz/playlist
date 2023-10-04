<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/playerr', 'Home::index');
$routes->post('/create', 'Home::create');
$routes->get('/playlists/(:any)', 'Home::playlists/$1');
$routes->get('/search', 'Home::search');
$routes->post('/upload', 'Home::upload');
$routes->post('/add', 'Home::add');