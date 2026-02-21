<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('comic/add', 'Admin\Comic::create');
});

    $routes->get('/home', 'Home::index');

    // Auth Routes
    $routes->get('login', 'Auth::index');
    $routes->post('login/auth', 'Auth::auth');
    $routes->get('logout', 'Auth::logout');

    $routes->group('admin', function($routes) {
    // Route Dashboard
    $routes->get('dashboard', 'Admin\Dashboard::index');
    
    // Route Kelola Komik
    $routes->get('komik', 'Admin\Komik::index');

    $routes->get('komik/create', 'Admin\Komik::create');
    $routes->post('komik/save', 'Admin\Komik::save');

    $routes->get('komik/edit/(:num)', 'Admin\Komik::edit/$1'); 
    $routes->post('komik/update/(:num)', 'Admin\Komik::update/$1');

    $routes->post('komik/delete/(:num)', 'Admin\Komik::delete/$1');

    // Route Kelola Users
    $routes->get('users', 'Admin\Users::index');

    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
});

    // --- Route Publik (Frontend Utama untuk User) ---
    $routes->get('/', 'Home::index');           // Homepage
    $routes->get('search', 'Katalog::search');  // Search (?q=keyword)
    $routes->get('popular', 'Katalog::popular');// Halaman Populer
    $routes->get('genres', 'Katalog::genres');  // Halaman Genre

    // --- Route Private (Akun User) ---
    $routes->group('user', function($routes) {
    // Profil
    $routes->get('profile', 'User::profile');
    $routes->post('profile/update', 'User::updateProfile');
    
    // Library
    $routes->get('library', 'User::library'); 
    $routes->get('bookmarks', 'User::library/bookmarks');
    $routes->get('history', 'User::library/history');
    
    // Pengaturan
    $routes->get('settings', 'User::settings');
    $routes->post('settings/password', 'User::updatePassword');
});