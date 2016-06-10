<?php

Route::group(['prefix' => backend_url_segment(), 'as' => 'backend.', 'middleware' => ['backend']], function () {

    /**********************************************************************
     * System
     **********************************************************************/
    Route::get('/settings', ['as' => 'settings', 'uses' => 'SystemController@settings']);
    Route::get('/about', ['as' => 'about', 'uses' => 'SystemController@about']);
    Route::get('/update', ['as' => 'update', 'uses' => 'SystemController@update']);
    Route::get('/phpinfo', ['as' => 'phpinfo', 'uses' => 'SystemController@phpInfo']);

    /**********************************************************************
     * Dashboard
     **********************************************************************/
    Route::get('/', ['as' => 'dashboard', 'uses' => 'SystemController@about']);
});

Route::group(['as' => 'api.', 'middleware' => ['backend']], function () {
    /**********************************************************************
     * Settings
     **********************************************************************/
    RouteAPI::post('settings.update', ['as' => 'settings.update', 'uses' => 'API\SettingsController@post']);

    /**********************************************************************
     * Cache
     **********************************************************************/
    RouteAPI::delete('cache.clear', ['as' => 'cache.clear', 'uses' => 'API\CacheController@deleteClear']);

    /**********************************************************************
     * Notifications
     **********************************************************************/
    RouteAPI::get('notifications.recent', ['as' => 'notifications.recent', 'uses' => 'API\NotificationController@recent',]);
    RouteAPI::put('notifications.read', ['as' => 'notifications.read', 'uses' => 'API\NotificationController@markAsRead',]);
});
