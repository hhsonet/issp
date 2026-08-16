<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index', ['as' => 'home']);
$routes->get('health', 'Home::health', ['as' => 'health']);
$routes->post('contact', 'Home::contact', ['as' => 'contact']);
$routes->get('login', 'Auth::login', ['filter' => 'guest', 'as' => 'login']);
$routes->post('login', 'Auth::attemptLogin', ['filter' => 'guest', 'as' => 'login.attempt']);
$routes->get('signup', 'Auth::signup', ['as' => 'signup']);
$routes->post('signup', 'Auth::attemptSignup', ['as' => 'signup.attempt']);
$routes->get('forgot-password', 'Auth::forgotPassword', ['as' => 'password.request']);
$routes->post('forgot-password', 'Auth::sendResetLink', ['as' => 'password.email']);
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth', 'as' => 'dashboard']);
$routes->get('profile', 'Dashboard::profile', ['filter' => 'auth', 'as' => 'profile']);
$routes->post('profile', 'Dashboard::updateProfile', ['filter' => 'auth', 'as' => 'profile.update']);
$routes->post('profile/password', 'Dashboard::updatePassword', ['filter' => 'auth', 'as' => 'profile.password']);
$routes->get('apply', 'Internship::apply', ['filter' => 'auth', 'as' => 'apply']);
$routes->post('apply', 'Internship::submit', ['filter' => 'auth', 'as' => 'apply.submit']);
$routes->get('applications', 'Internship::index', ['filter' => 'auth', 'as' => 'applications']);
$routes->get('applications/(:num)', 'Internship::show/$1', ['filter' => 'auth', 'as' => 'applications.show']);
$routes->get('admin/application-rounds', 'Internship::rounds', ['filter' => 'auth', 'as' => 'applicationRounds']);
$routes->post('admin/application-rounds', 'Internship::storeRound', ['filter' => 'auth', 'as' => 'applicationRounds.store']);
$routes->post('logout', 'Auth::logout', ['filter' => 'auth', 'as' => 'logout']);
