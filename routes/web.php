<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// build URI for AJAX and share it so it's available in all views for JS scripts to use. From there we can add it to VueJS
$segment_1 = Request::segment(1);
$segment_2 = Request::segment(2);
$segment_3 = Request::segment(3);

if($segment_1 == "dms" && $segment_2 == 'hotel'){
    $ajax_uri = "/" . $segment_1 . "/hotel/" . $segment_3 . "/";
    View::share('DOMETIC_HOTEL_ID', $segment_3);
}else{
    $ajax_uri = "/" . $segment_1 . "/";
}
View::share('AJAX_URI', $ajax_uri);
 
Auth::routes();

Route::get('/login', 'Auth\LoginController@show_hotel_login')->name("login");
Route::post('/login', 'Auth\LoginController@handle_hotel_login');

// Attendant Routes
$router->group(['middleware' => ['choose_db', 'auth:hotel_user', 'user_role:3', 'is_active']], function() use($router){
    $router->get('/', ['uses' => 'AttendantAppController@index'])->name("attendant_app");
});


// Admin routes
Route::get('/admin/login', 'Auth\LoginController@show_hotel_login');
Route::post('/admin/login', 'Auth\LoginController@handle_hotel_login');

$router->group(['middleware' => ['choose_db', 'auth:hotel_user', 'user_role:2', 'is_active'], 'prefix' => 'admin'], function() use($router){
    $router->get('/', ['uses' => 'admin_hotel\AdminController@index'])->name("hotel_admin");
    $router->post('/',[
        'uses'  => 'admin_hotel\AdminController@update_setup',
        'as'    => 'update_setup'
    ]);
    
    $router->get('users', ['uses' => 'admin_hotel\HotelUserController@index'])->name("hotel_admin_users");
    $router->post('users',['uses'  => 'admin_dms\hotel_controllers\HotelUserController@add_new']);
    $router->put('users',['uses'  => 'admin_dms\hotel_controllers\HotelUserController@update']);
        
    $router->get('floors', ['uses' => 'admin_hotel\FloorController@index'])->name("hotel_admin_rooms");
    $router->post('floors',['uses'  => 'admin_dms\hotel_controllers\FloorController@add_new']);
    $router->post('floors/batch_sort_update',['uses'  => 'admin_dms\hotel_controllers\FloorController@batch_sort_update']);
    $router->put('floors',['uses'  => 'admin_dms\hotel_controllers\FloorController@update']);
    
    
    $router->get('rooms/{floor_id}', ['uses' => 'admin_hotel\RoomController@index']);
    $router->post('rooms',['uses'  => 'admin_dms\hotel_controllers\RoomController@add_new']);
    $router->post('rooms/batch_new',['uses'  => 'admin_dms\hotel_controllers\RoomController@batch_new']);
    $router->post('rooms/batch_sort_update',['uses'  => 'admin_dms\hotel_controllers\RoomController@batch_sort_update']);
    $router->put('rooms',['uses'  => 'admin_dms\hotel_controllers\RoomController@update']);
    
    $router->get('menu', ['uses' => 'admin_hotel\ArticleCategoryController@index'])->name("hotel_admin_articles");
    $router->post('article_category',['uses'  => 'admin_dms\hotel_controllers\ArticleCategoryController@add_new']);
    $router->post('article_category/batch_sort_update',['uses'  => 'admin_dms\hotel_controllers\ArticleCategoryController@batch_sort_update']);
    $router->put('article_category',['uses'  => 'admin_dms\hotel_controllers\ArticleCategoryController@update']);

    $router->get('articles/{category_id}',['uses'  => 'admin_hotel\ArticleController@index']);
    $router->post('articles',['uses'  => 'admin_dms\hotel_controllers\ArticleController@add_new']);
    $router->post('articles/batch_sort_update',['uses'  => 'admin_dms\hotel_controllers\ArticleController@batch_sort_update']);
    $router->put('articles',['uses'  => 'admin_dms\hotel_controllers\ArticleController@update']);

    $router->get('messages', ['uses' => 'admin_hotel\MessageController@index'])->name("hotel_admin_messages");
    $router->post('messages',['uses'  => 'admin_dms\hotel_controllers\MessageController@add_new']);
    $router->post('messages/batch_sort_update',['uses'  => 'admin_dms\hotel_controllers\MessageController@batch_sort_update']);
    $router->put('messages',['uses'  => 'admin_dms\hotel_controllers\MessageController@update']);
});


// DMS Admin Routes
Route::get('/dms/login', 'Auth\LoginController@show_dms_login')->name("login_dms");
Route::post('/dms/login', 'Auth\LoginController@handle_dms_login');

$router->group(['middleware' => 'auth:dms', 'prefix' => 'dms'], function () use ($router) {
    $router->get('/', ['uses' => 'admin_dms\AdminController@index']);
    
    // Client Management routes
    $router->get('client/new',[
        'uses'  => 'admin_dms\AdminController@new_client',
        'as'    => 'new_client'
    ]);
    $router->post('client/new', [
        'uses'  => 'admin_dms\AdminController@post_new_client',
        'as'    => 'post_new_client'
    ]);
    $router->get('client/{client_id}/edit', [
        'uses'  => 'admin_dms\AdminController@edit_client',
        'as'    => 'edit_client'
    ]);
    $router->post('client/{client_id}/edit', [
        'uses'  => 'admin_dms\AdminController@post_edit_client',
        'as'    => 'post_edit_client'
    ]);
    
    
    
    // Hotel Routes
    $router->group(['middleware' => 'choose_db', 'prefix' => 'hotel'], function() use($router){
        $router->get('/{dometic_hotel_id}',[
            'uses'  => 'admin_dms\hotel_controllers\HotelController@index',
            'as'    => 'dometic_hotel_admin'
        ]);
        
        $router->post('/{dometic_hotel_id}',[
            'uses'  => 'admin_dms\hotel_controllers\HotelController@update_hotel_setup',
            'as'    => 'update_hotel_setup'
        ]);
        
        
        $router->get('/{dometic_hotel_id}/users',[
            'uses'  => 'admin_dms\hotel_controllers\HotelUserController@index',
            'as'    => 'dometic_hotel_users'
        ]);
        $router->post('/{dometic_hotel_id}/users',['uses'  => 'admin_dms\hotel_controllers\HotelUserController@add_new']);
        $router->put('/{dometic_hotel_id}/users',['uses'  => 'admin_dms\hotel_controllers\HotelUserController@update']);
        
         
        $router->get('/{dometic_hotel_id}/floors',[
            'uses'  => 'admin_dms\hotel_controllers\FloorController@index',
            'as'    => 'dometic_hotel_rooms'
        ]);
        $router->post('/{dometic_hotel_id}/floors',['uses'  => 'admin_dms\hotel_controllers\FloorController@add_new']);
        $router->post('/{dometic_hotel_id}/floors/batch_sort_update',['uses'  => 'admin_dms\hotel_controllers\FloorController@batch_sort_update']);
        $router->put('/{dometic_hotel_id}/floors',['uses'  => 'admin_dms\hotel_controllers\FloorController@update']);
        
        
        $router->get('/{dometic_hotel_id}/rooms/{floor_id}',['uses'  => 'admin_dms\hotel_controllers\RoomController@index']);
        $router->post('/{dometic_hotel_id}/rooms',['uses'  => 'admin_dms\hotel_controllers\RoomController@add_new']);
        $router->post('/{dometic_hotel_id}/rooms/batch_new',['uses'  => 'admin_dms\hotel_controllers\RoomController@batch_new']);
        $router->post('/{dometic_hotel_id}/rooms/batch_sort_update',['uses'  => 'admin_dms\hotel_controllers\RoomController@batch_sort_update']);
        $router->put('/{dometic_hotel_id}/rooms',['uses'  => 'admin_dms\hotel_controllers\RoomController@update']);
        
        
        $router->get('/{dometic_hotel_id}/menu',[
            'uses'  => 'admin_dms\hotel_controllers\ArticleCategoryController@index',
            'as'    => 'dometic_hotel_articles'
        ]);
        $router->post('/{dometic_hotel_id}/article_category',['uses'  => 'admin_dms\hotel_controllers\ArticleCategoryController@add_new']);
        $router->post('/{dometic_hotel_id}/article_category/batch_sort_update',['uses'  => 'admin_dms\hotel_controllers\ArticleCategoryController@batch_sort_update']);
        $router->put('/{dometic_hotel_id}/article_category',['uses'  => 'admin_dms\hotel_controllers\ArticleCategoryController@update']);
        
        $router->get('/{dometic_hotel_id}/articles/{category_id}',['uses'  => 'admin_dms\hotel_controllers\ArticleController@index']);
        $router->post('/{dometic_hotel_id}/articles',['uses'  => 'admin_dms\hotel_controllers\ArticleController@add_new']);
        $router->post('/{dometic_hotel_id}/articles/batch_sort_update',['uses'  => 'admin_dms\hotel_controllers\ArticleController@batch_sort_update']);
        $router->put('/{dometic_hotel_id}/articles',['uses'  => 'admin_dms\hotel_controllers\ArticleController@update']);
        
        
        $router->get('/{dometic_hotel_id}/messages',[
            'uses'  => 'admin_dms\hotel_controllers\MessageController@index',
            'as'    => 'dometic_hotel_messages'
        ]);
        $router->post('/{dometic_hotel_id}/messages',['uses'  => 'admin_dms\hotel_controllers\MessageController@add_new']);
        $router->post('/{dometic_hotel_id}/messages/batch_sort_update',['uses'  => 'admin_dms\hotel_controllers\MessageController@batch_sort_update']);
        $router->put('/{dometic_hotel_id}/messages',['uses'  => 'admin_dms\hotel_controllers\MessageController@update']);
    });
});