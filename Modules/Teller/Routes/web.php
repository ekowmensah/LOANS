<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::prefix('teller')->group(function () {
    Route::get('/', 'TellerController@index');
    Route::post('/search', 'TellerController@search_account');
    Route::post('/transaction', 'TellerController@process_transaction');
});
