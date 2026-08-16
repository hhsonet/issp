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
$routes->get('apply/(:segment)', 'Internship::apply/$1', ['filter' => 'auth', 'as' => 'apply.round']);
$routes->get('apply/departments/(:num)', 'Internship::departments/$1', ['filter' => 'auth', 'as' => 'apply.departments']);
$routes->post('apply', 'Internship::submit', ['filter' => 'auth', 'as' => 'apply.submit']);
$routes->post('apply/(:segment)', 'Internship::submit/$1', ['filter' => 'auth', 'as' => 'apply.round.submit']);
$routes->get('applications', 'Internship::index', ['filter' => 'auth', 'as' => 'applications']);
$routes->get('applications/(:segment)', 'Internship::show/$1', ['filter' => 'auth', 'as' => 'applications.show']);
$routes->get('applications/(:num)', 'Internship::show/$1', ['filter' => 'auth']);
$routes->get('applications/(:segment)/edit', 'Internship::edit/$1', ['filter' => 'auth', 'as' => 'applications.edit']);
$routes->post('applications/(:segment)/edit', 'Internship::updateApplication/$1', ['filter' => 'auth', 'as' => 'applications.update']);
$routes->get('admin/calls', 'Internship::rounds', ['filter' => 'admin', 'as' => 'applicationRounds']);
$routes->get('admin/calls/create', 'Internship::createRound', ['filter' => 'admin', 'as' => 'applicationRounds.create']);
$routes->get('admin/calls/(:num)/edit', 'Internship::editRound/$1', ['filter' => 'admin', 'as' => 'applicationRounds.edit']);
$routes->post('admin/calls', 'Internship::storeRound', ['filter' => 'admin', 'as' => 'applicationRounds.store']);
$routes->post('admin/calls/(:num)', 'Internship::updateRound/$1', ['filter' => 'admin', 'as' => 'applicationRounds.update']);
$routes->post('admin/calls/(:num)/status', 'Internship::toggleRoundStatus/$1', ['filter' => 'admin', 'as' => 'applicationRounds.status']);
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('dashboard', 'Admin::dashboard', ['as' => 'admin.dashboard']);
    $routes->get('applications', 'Admin::applications', ['as' => 'admin.applications']);
    $routes->get('application', 'Admin::applications');
    $routes->post('application/(:segment)/toggle-edit-access', 'Admin::toggleEditAccess/$1', ['as' => 'admin.application.toggleEditAccess']);
    $routes->get('applications/(:segment)', 'Admin::applicationView/$1', ['as' => 'admin.applications.view']);
    $routes->get('application/(:segment)', 'Admin::applicationView/$1');
    $routes->post('application/(:segment)/edit-access', 'Admin::toggleApplicationEditAccess/$1', ['as' => 'admin.application.editAccess']);
    $routes->post('application/(:segment)/delete', 'Admin::deleteApplication/$1', ['as' => 'admin.application.delete']);
    $routes->post('applications/(:segment)/grant-edit', 'Admin::grantEditAccess/$1', ['as' => 'admin.applications.grantEdit']);
    $routes->post('applications/(:segment)/revoke-edit', 'Admin::revokeEditAccess/$1', ['as' => 'admin.applications.revokeEdit']);
    $routes->post('applications/(:segment)/delete', 'Admin::deleteApplication/$1', ['as' => 'admin.applications.delete']);
    $routes->get('users', 'Admin::users', ['as' => 'admin.users']);
    $routes->post('users/(:num)/status', 'Admin::toggleStatus/$1', ['as' => 'admin.users.status']);
    $routes->post('users/promote/(:segment)', 'Admin::promote/$1', ['as' => 'admin.users.promote']);
});
$routes->post('logout', 'Auth::logout', ['filter' => 'auth', 'as' => 'logout']);
