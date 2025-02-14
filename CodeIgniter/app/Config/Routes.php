
 <?php 

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes 
 */

// Home routes
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');

// Authentication routes
$routes->get('sign-in', 'AuthController::signIn'); // Login page
$routes->get('login', 'AuthController::login'); // Login page
$routes->get('sign-up', 'AuthController::signUp'); // Registration page
$routes->post('login/process', 'AuthController::loginProcess'); // Process login
$routes->post('register/process', 'AuthController::registerProcess'); // Process registration
$routes->get('logout', 'AuthController::logout'); // Logout

// User routes
$routes->get('users', 'UserController::index'); // List users
$routes->get('add-user', 'UserController::addUser'); // User addition page
$routes->post('users/save', 'UserController::saveUser'); // Create user (POST)
$routes->post('users/save/(:num)', 'UserController::saveUser/$1'); // Edit user (POST)
$routes->get('users/save/(:num)', 'UserController::saveUser/$1'); // Edit user page
$routes->get('users/delete/(:num)', 'UserController::delete/$1'); // Update user to mark as deleted


// Recipe routes
$routes->get('recipes', 'RecipeController::index'); // List recipes
$routes->get('add-recipe', 'RecipeController::addRecipe'); // Recipe addition page
$routes->post('recipes/save', 'RecipeController::saveRecipe'); // Create recipe (POST)
$routes->post('recipes/save/(:num)', 'RecipeController::saveRecipe/$1'); // Edit recipe (POST)
$routes->get('recipes/save/(:num)', 'RecipeController::saveRecipe/$1'); // Edit recipe page
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

