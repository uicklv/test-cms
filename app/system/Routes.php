<?php
/**
* ROUTES
*/

// Example:
//Route::set('login', 'profile@login')
//    ->name('profile')
//    ->where('id', '[0-9]+')
//    ->where('name', '[A-Za-z]+')
//    ->where(['id' => '[0-9]+', 'name' => '[a-z]+']);
//Do not remove
Route::set('app/data/cvs/{slug}', 'page@file_download')->name('file_download');
Route::set('es.png', 'page@email_status')->name('e_image');

// Profile
Route::set('login', 'profile@login')->name('login');
Route::set('profile', 'profile@profile')->name('profile');
Route::set('restore-password', 'profile@restore_password')->name('restore');
Route::set('restore-process', 'profile@restore_process')->name('restore-process');
Route::set('register', 'profile@register')->name('register');
Route::set('email-confirmation', 'profile@email_confirmation')->name('email_confirmation');
Route::set('get-role', 'profile@get_role')->name('get_role');
Route::set('salary-survey', 'profile@salary_survey')->name('salary_survey');

// Jobs
Route::set('job/{slug}', 'jobs@view')->name('job');
Route::set('saved-jobs', 'jobs@saved_jobs')->name('saved_jobs');
Route::set('unsubscribe/{slug}', 'jobs@unsubscribe');

// Blogs
Route::set('blog/{slug}', 'blogs@view')->name('blog');

// Additional pages
Route::set('terms-and-conditions', 'page@terms-and-conditions')->name('terms');
Route::set('privacy-policy', 'page@privacy-policy')->name('privacy');
Route::set('contact-us', 'about_us@contact_us')->name('contact_us');

// Resources
Route::set('resources', 'page@resources')->name('resource_center');

// Micro-sites, landings, etc
Route::set('microsite/{ref}', 'microsite@index')->name('microsite');
Route::set('landing/{slug}', 'landing@index')->name('landing');
Route::set('contact-landing', 'landing@contact')->name('contact_landing');
Route::set('locations', 'page@locations')->name('locations');

// Learning & development, members growth club, etc
Route::set('learning-development-resources', 'learning_development@index')->name('ld_resources');
Route::set('learning-development-resource/{slug}', 'learning_development@view')->name('ld_resource');
Route::set('resources/category', 'learning_development@category')->name('rs_category');
Route::set('members-growth-club/article/{slug}', 'members_growth_club@view')->name('growth_club');

// Other (need to clear in new projects)
Route::set('services', 'page@services')->name('services');
Route::set('specialisms', 'page@specialisms')->name('specialisms');
Route::set('edison', 'page@edison')->name('edison');
Route::set('c-suite', 'page@c-suite')->name('c_suite');
Route::set('tech-community', 'page@tech_community')->name('tech_community');
Route::set('seeking-talent', 'page@looking')->name('seeking_talent');
Route::set('tactical-solutions', 'page@tactical_solutions')->name('t_solutions');
Route::set('operational-solutions', 'page@operational_solutions')->name('o_solutions');

// Talent
Route::set('search-anon-profile', 'talent/anonymous_profile@search')->name('search_profile'); // anonymous
Route::set('talent/anonymous_profile/{slug}', 'talent/anonymous_profile@view')->name('anonymous_profile');
Route::set('candidate-alert/{id}', 'talent/anonymous_profile@candidate_alert')->name('candidate_alert');
Route::set('request-interview/{id}', 'talent/anonymous_profile@request-interview')->name('request_interview');
Route::set('request-info/{id}', 'talent/anonymous_profile@request_info')->name('request_info');
Route::set('request-cv/{id}', 'talent/anonymous_profile@request_cv')->name('request_cv');
Route::set('find-postcode', 'talent/anonymous_profile@postcode')->name('find-postcode');

Route::set('talent/open_profile/{slug}', 'talent/open_profile@view')->name('open_profile'); // open
Route::set('open-candidate-alert/{id}', 'talent/open_profile@candidate_alert')->name('open_candidate_alert');
Route::set('open-request-interview/{id}', 'talent/open_profile@request_interview')->name('open_request_interview');
Route::set('open-request-info/{id}', 'talent/open_profile@request_info')->name('open_request_info');
Route::set('feedback', 'talent/open_profile@feedback')->name('feedback');
Route::set('open-book', 'talent/open_profile@book')->name('open_book');

Route::set('talent/hotlist/{slug}', 'talent/hotlist@index')->name('hotlist');
Route::set('talent/shortlist/{slug}', 'talent/shortlist@index')->name('shortlist');

//Form builder
Route::set('form/{slug}', 'form@index')->name('form');
Route::set('form-save', 'form@save')->name('form-save');
Route::set('form-pdf/{id}', 'form@pdf')->name('form-pdf');

//Shop
Route::set('shop/product/{slug}', 'shop@view');
Route::set('shop/search', 'shop@search')->name('shop-search');

// Upload image
Route::set('upload-image', 'page@upload_image')->name('upload_image');
Route::set('url-info', 'page@url_info')->name('url-info');

/* End of file */
