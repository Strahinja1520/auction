<?php

use App\Core\Route;

return [


    // ! REGISTER
    Route::get("Main", "/^user\/register\/?$/", "getRegister"),
    Route::post("Main", "/^user\/register\/?$/", "postRegister"),

    // ! LOGIN
    Route::get("Main", "/^user\/login\/?$/", "getLogin"),
    Route::post("Main", "/^user\/login\/?$/", "postLogin"),

    // ! LOG-OUT
    Route::get("Main", "/^user\/log-out\/?$/", "logout"),

    // ! USER PROFILE
    Route::get("User", "/^user\/profile\/?$/", "profile"),
    Route::post("User", "/^user\/profile\/?$/", "postLogin"),
    Route::post("User", "/^user\/profile\/edit\/?$/", "edit"),

    // ! AUCTION
    Route::get("Auction", "/^auction\/create\/?$/", "getCreate"),
    Route::post("Auction", "/^auction\/create\/?$/", "postCreate"),
    Route::get("Auction", "/^auction\/edit\/([0-9]+)\/?$/", "getEdit"),
    Route::post("Auction", "/^auction\/edit\/([0-9]+)\/?$/", "postEdit"),
    Route::get("Auction", "/^auction\/delete\/([0-9]+)\/?$/", "delete"),

    Route::get("Category", "/^categories\/?$/", "showAll"),
    Route::get("Category", "/^category\/([0-9]+)\/?$/", "show"),
    Route::get("Auction", "/^auction\/([0-9]+)\/?$/", "show"),
    Route::post("Auction", "/^search\/?$/", "search"),

    // ! OFFER
    Route::any("Offer", "/^offer\/create\/?$/", "create"),

    // ! USER
    Route::any("User", "/^admin-dashboard\/user\/delete\/([0-9]+)\/?$/","deleteFromAdmin"),
    Route::any("User", "/^moderator-dashboard\/user\/delete\/([0-9]+)\/?$/","deleteFromModerator"),
    Route::get("User", "/^admin-dashboard\/user\/([0-9]+)\/?$/","userAuctions"),
    Route::get("User", "/^moderator-dashboard\/user\/([0-9]+)\/?$/","modUserAuctions"),

    // ! CATEGORY 
    Route::any("Category", "/^admin-dashboard\/category\/delete\/([0-9]+)\/?$/","deleteFromAdmin"),

    // ! ADMIN
    Route::get("Main","/^admin-dashboard\/?$/","admin"),
    Route::get("Main","/^admin-dashboard\/users\/?$/","adminUsers"),
    Route::get("Main","/^admin-dashboard\/categories\/?$/","adminCategories"),
    Route::get("Main","/^admin-dashboard\/create-moderator\/?$/","getCreateModerator"),
    Route::post("Main","/^admin-dashboard\/create-moderator\/?$/","postCreateModerator"),
    Route::get("Main","/^admin-dashboard\/create-category\/?$/","getCreateCategory"),
    Route::post("Main","/^admin-dashboard\/create-category\/?$/","postCreateCategory"),
    Route::get("Auction","/^admin-dashboard\/auction\/delete\/([0-9]+)\/?$/","deleteAdminAction"),

    // ! MODERATOR
    Route::get("Main","/^moderator-dashboard\/?$/","moderatorUsers"),
    Route::get("Auction","/^moderator-dashboard\/delete\/([0-9]+)\/?$/","deleteModeratorAction"),

    // ! PAGE NOT FOUND
    Route::any("Main", "/^404*$/", "pageNotFound"),
    
    
    //! Fall back route
    // Route::any("Main", "/^.*$/", "home") 
    Route::any("Main", "/^$/", "home") 
];