<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// --- Auth Routes ---
$routes->get('login', 'Auth::index');
$routes->post('login/auth', 'Auth::auth');
$routes->get('logout', 'Auth::logout');
// Auth Register
$routes->get('register', 'Auth::register');
$routes->post('register/process', 'Auth::processRegister');

// --- Admin Routes ---
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'adminAuth'], static function ($routes) {
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
    
    // Khusus Pages
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
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');
$routes->get('search', 'User\Katalog::search');  
$routes->get('popular', 'User\Katalog::popular');
$routes->get('genres', 'User\Katalog::genres');  
$routes->get('komik/(:segment)', 'User\Katalog::detail/$1'); 
$routes->get('baca/(:segment)/(:segment)', 'User\Katalog::read/$1/$2');

// --- Route Private (Akun User) ---
$routes->group('user', ['namespace' => 'App\Controllers\User'], static function ($routes) {
    // Profil
    $routes->get('profile', 'User::profile');
    $routes->post('profile/update', 'User::updateProfile');
    
    // Library
    $routes->get('library', 'User::library'); 
    $routes->get('bookmarks', 'User::library/bookmarks');
    $routes->post('bookmark/toggle/(:num)', 'User::toggleBookmark/$1');
    $routes->get('history', 'User::library/history');   
    
    // Pengaturan
    $routes->get('settings', 'User::settings');
    $routes->post('settings/password', 'User::updatePassword');
    $routes->post('settings/delete', 'User::deleteAccount');
});


    // --- REST API ROUTES ---
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    // Auth Routes (Flutter)
    $routes->post('auth/login', 'Auth::login');
    $routes->post('auth/register', 'Auth::register');

    // Komik Routes (CI4 dan Flutter)
    $routes->get('komik/genres', 'Komik::genres');
    $routes->resource('komik', ['controller' => 'Komik']); // route utama untuk API CI4, flutter bisa pakai yang sama

    // Chapter Route (Flutter)
    $routes->get('chapter/(:num)', 'Chapter::show/$1');

    // User Routes (Library & Bookmark utk Flutter)
    $routes->get('user/library/(:num)', 'User::library/$1');
    $routes->post('user/bookmark', 'User::toggleBookmark');
    $routes->get('user/history/(:num)', 'User::history/$1');
});