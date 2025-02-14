<?php
use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('sign-in', 'AuthController::signIn'); // Login page
$routes->get('login', 'AuthController::login'); // Login page
$routes->get('sign-up', 'AuthController::signUp'); // Registration page
$routes->get('add-user', 'AuthController::addUser'); // User addition page
$routes->get('add-recipe', 'AuthController::addRecipe'); // User addition page
$routes->get('users/save/(:num)', 'UserController::saveUser/$1'); // Edit user page
$routes->get('recipes/save/(:num)', 'RecipeController::saveRecipe/$1'); // Edit user page
$routes->get('users', 'UserController::index'); // List users
$routes->get('recipes', 'RecipeController::index'); // List recipes
$routes->get('comments', 'CommentController::index'); // List comments
$routes->get('favorites', 'FavoriteController::index'); // List favorites
$routes->get('logout', 'AuthController::logout'); // Logout

$routes->post('login/process', 'AuthController::loginProcess'); // Process login
$routes->post('register/process', 'AuthController::registerProcess'); // Process registration
$routes->post('users/save', 'UserController::saveUser'); // Create user (POST)
$routes->post('recipes/save', 'RecipeController::saveRecipe'); // Create recipe (POST)
$routes->post('users/save/(:num)', 'UserController::saveUser/$1'); // Edit user (POST)
$routes->get('users/delete/(:num)', 'UserController::delete/$1'); // Delete user
$routes->post('recipes/save/(:num)', 'RecipeController::saveRecipe/$1'); // Edit recipe (POST)
$routes->get('recipes/delete/(:num)', 'RecipeController::delete/$1'); // Delete recipe

// Comment routes
$routes->get('comments', 'CommentController::index'); // List comments
$routes->get('add-comment', 'CommentController::addComment'); // Comment addition page
$routes->post('comments/save', 'CommentController::saveComment'); // Create comment (POST)
$routes->post('comments/save/(:num)', 'CommentController::saveComment/$1'); // Edit comment (POST)
$routes->get('comments/save/(:num)', 'CommentController::saveComment/$1'); // Edit comment page
$routes->get('comments/delete/(:num)', 'CommentController::delete/$1'); // Delete comment

// Favorite routes
$routes->get('favorites', 'FavoriteController::index'); // List favorites
$routes->get('add-favorite', 'FavoriteController::addFavorite'); // Favorite addition page
$routes->post('favorites/save', 'FavoriteController::saveFavorite'); // Create favorite (POST)
$routes->post('favorites/save/(:num)', 'FavoriteController::saveFavorite/$1'); // Edit favorite (POST)
$routes->get('favorites/save/(:num)', 'FavoriteController::saveFavorite/$1'); // Edit favorite page
$routes->get('favorites/delete/(:num)', 'FavoriteController::delete/$1'); // Delete favorite

