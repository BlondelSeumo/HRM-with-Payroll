<?php

/**
 * -----------------------------------------------------------------
 * NOTE : There is two routes has a name (user & group),
 * any change in these two route's name may cause an issue
 * if not modified in all places that used in (e.g Chatify class,
 * Controllers, chatify javascript file...).
 * -----------------------------------------------------------------
 */

use Illuminate\Support\Facades\Route;

/*
* This is the main app route [Chatify Messenger]
*/

    Route::get('/', 'MessagesController@index')->name(config('chatify.routes.prefix'))->middleware(['auth', 'XSS']);

    /**
     *  Fetch info for specific id [user/group]
     */
    Route::post('/idInfo', 'MessagesController@idFetchData')->middleware(['auth', 'XSS']);

    /**
     * Send message route
     */
    Route::post('/sendMessage', 'MessagesController@send')->name('send.message')->middleware(['auth', 'XSS']);

    /**
     * Fetch messages
     */
    Route::post('/fetchMessages', 'MessagesController@fetch')->name('fetch.messages')->middleware(['auth', 'XSS']);

    /**
     * Download attachments route to create a downloadable links
     */
    Route::get('/download/{fileName}', 'MessagesController@download')->name(config('chatify.attachments.download_route_name'))->middleware(['auth', 'XSS']);

    /**
     * Authentication for pusher private channels
     */
    Route::post('/chat/auth', 'MessagesController@pusherAuth')->name('pusher.auth')->middleware(['auth', 'XSS']);

    /**
     * Make messages as seen
     */
    Route::post('/makeSeen', 'MessagesController@seen')->name('messages.seen')->middleware(['auth', 'XSS']);

    /**
     * Get contacts
     */
    Route::get('/getContacts', 'MessagesController@getContacts')->name('contacts.get')->middleware(['auth', 'XSS']);

    /**
     * Update contact item data
     */
    Route::post('/updateContacts', 'MessagesController@updateContactItem')->name('contacts.update')->middleware(['auth', 'XSS']);


    /**
     * Star in favorite list
     */
    Route::post('/star', 'MessagesController@favorite')->name('star')->middleware(['auth', 'XSS']);

    /**
     * get favorites list
     */
    Route::post('/favorites', 'MessagesController@getFavorites')->name('favorites')->middleware(['auth', 'XSS']);

    /**
     * Search in messenger
     */
    Route::get('/search', 'MessagesController@search')->name('search')->middleware(['auth', 'XSS']);

    /**
     * Get shared photos
     */
    Route::post('/shared', 'MessagesController@sharedPhotos')->name('shared')->middleware(['auth', 'XSS']);

    /**
     * Delete Conversation
     */
    Route::post('/deleteConversation', 'MessagesController@deleteConversation')->name('conversation.delete')->middleware(['auth', 'XSS']);

    /**
     * Delete Message
     */
    Route::post('/deleteMessage', 'MessagesController@deleteMessage')->name('message.delete')->middleware(['auth', 'XSS']);

    /**
     * Update setting
     */
    Route::post('/updateSettings', 'MessagesController@updateSettings')->name('avatar.update')->middleware(['auth', 'XSS']);

    /**
     * Set active status
     */
    Route::post('/setActiveStatus', 'MessagesController@setActiveStatus')->name('activeStatus.set')->middleware(['auth', 'XSS']);






    /*
* [Group] view by id
*/
    Route::get('/group/{id}', 'MessagesController@index')->name('group')->middleware(['auth', 'XSS']);

    /*
* user view by id.
* Note : If you added routes after the [User] which is the below one,
* it will considered as user id.
*
* e.g. - The commented routes below :
*/
    // Route::get('/route', function(){ return 'Munaf'; }); // works as a route
    Route::get('/{id}', 'MessagesController@index')->name('user')->middleware(['auth', 'XSS']);
    // Route::get('/route', function(){ return 'Munaf'; }); // works as a user id
