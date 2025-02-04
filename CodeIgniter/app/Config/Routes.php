<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('sign-in', 'AuthController::signIn'); // Página de login
$routes->get('login', 'AuthController::login'); // Página de login
$routes->get('sign-up', 'AuthController::signUp'); // Página de registro
$routes->post('login/process', 'AuthController::loginProcess'); // Procesar login
$routes->get('register', 'AuthController::register'); // Crear usuario (POST)
$routes->post('register/process', 'AuthController::registerProcess'); // Procesar registro





$routes->get('users', 'UserController::index'); // Listar usuarios
$routes->get('recipes', 'RecipeController::index'); // Listar recetas
$routes->get('comments', 'CommentController::index'); // Listar recetas
$routes->get('favorites', 'FavoriteController::index'); // Listar recetas
$routes->get('users/save', 'UserController::saveUser'); // Mostrar formulario para crear usuario
$routes->get('users/save/(:num)', 'UserController::saveUser/$1'); // Mostrar formulario para editar usuario
$routes->post('users/save', 'UserController::saveUser'); // Crear usuario (POST)
$routes->post('users/save/(:num)', 'UserController::saveUser/$1'); // Editar usuario (POST)
$routes->get('users/delete/(:num)', 'UserController::delete/$1'); // Eliminar usuario

$routes->get('logout', 'AuthController::logout'); // Cerrar sesión
$routes->get('dashboard', 'AuthController::dashboard'); // Assuming you have a controller named DashboardController

