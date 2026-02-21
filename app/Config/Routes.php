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

$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], static function ($routes) {
    // Route Dashboard
    $routes->get('dashboard', 'Dashboard::index');
    
    // Route Kelola Komik
    $routes->get('komik', 'Komik::index');

    $routes->get('komik/create', 'Komik::create');
    $routes->post('komik/save', 'Komik::save');

    $routes->get('komik/edit/(:num)', 'Komik::edit/$1'); 
    $routes->post('komik/update/(:num)', 'Komik::update/$1');
    $routes->post('komik/delete/(:num)', 'Komik::delete/$1');

    // Route Kelola Chapter
    $routes->get('chapter', 'Chapter::index');
    $routes->get('chapter/list/(:num)', 'Chapter::list/$1');
    $routes->get('chapter/add-chapter/(:num)', 'Chapter::addChapter/$1');
    $routes->post('chapter/save-chapter/(:num)', 'Chapter::saveChapter/$1');
    
    //Khusus Pages
    $routes->get('chapter/edit/(:num)', 'Chapter::edit/$1');
    $routes->post('chapter/update-page/(:num)', 'Chapter::updatePage/$1');
    $routes->post('chapter/delete-page/(:num)', 'Chapter::deletePage/$1');
    $routes->post('chapter/add-pages/(:num)', 'Chapter::addPages/$1');

    // Route Kelola Users
    $routes->get('users', 'Users::index');

    $routes->get('users/edit/(:num)', 'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
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