<?php


/******* Admin page *******/
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => 'web'
], function() {

    Route::any('/login', 'AuthController@login');
    Route::get('/logout', 'AuthController@logout');

    Route::post('menu/is_show', 'MenuController@changeIsShow');
    Route::resource('menu', 'MenuController');

    Route::post('info/is_show', 'InfoController@changeIsShow');
    Route::resource('info', 'InfoController');

    Route::post('faq/is_show', 'FaqController@changeIsShow');
    Route::resource('faq', 'FaqController');

    Route::post('insurance/is_show', 'InsuranceController@changeIsShow');
    Route::resource('insurance', 'InsuranceController');

    Route::post('order/is_show', 'OrderController@changeIsShow');
    Route::resource('order', 'OrderController');

    Route::post('request-policy/excel/{type}', 'RequestPolicyController@exportExcel');
    Route::post('request-policy/is_show', 'RequestPolicyController@changeIsShow');
    Route::get('request-policy/{type}', 'RequestPolicyController@index');
    Route::resource('request-policy', 'RequestPolicyController');

    Route::post('order-insurance/is_show', 'OrderInsuranceController@changeIsShow');
    Route::resource('order-insurance', 'OrderInsuranceController');

    Route::post('order-vacancy/is_show', 'OrderVacancyController@changeIsShow');
    Route::resource('order-vacancy', 'OrderVacancyController');

    Route::post('offer/is_show', 'OfferController@changeIsShow');
    Route::resource('offer', 'OfferController');

    Route::post('news/is_show', 'NewsController@changeIsShow');
    Route::resource('news', 'NewsController');

    Route::post('city/is_show', 'CityController@changeIsShow');
    Route::resource('city', 'CityController');

    Route::post('contacts/is_show', 'ContactsController@changeIsShow');
    Route::post('contacts/address', 'ContactsController@showNewAddress');
    Route::resource('contacts', 'ContactsController');

    Route::post('vacancy/is_show', 'VacancyController@changeIsShow');
    Route::resource('vacancy', 'VacancyController');

    Route::post('team/is_show', 'TeamController@changeIsShow');
    Route::resource('team', 'TeamController');

    Route::post('person/is_show', 'PersonController@changeIsShow');
    Route::resource('person', 'PersonController');

    Route::post('report-type/is_show', 'ReportTypeController@changeIsShow');
    Route::resource('report-type', 'ReportTypeController');

    Route::post('document-type/is_show', 'DocumentTypeController@changeIsShow');
    Route::resource('document-type', 'DocumentTypeController');

    Route::post('report/is_show', 'ReportController@changeIsShow');
    Route::resource('report', 'ReportController');

    Route::post('policy/excel', 'PolicyController@exportExcel');
    Route::post('policy/is_show', 'PolicyController@changeIsShow');
    Route::resource('policy', 'PolicyController');

    Route::get('client/reset/{id}', 'ClientController@resetPassword');
    Route::post('client/is_show', 'ClientController@changeIsShow');
    Route::resource('client', 'ClientController');

    Route::get('user/reset/{id}', 'UsersController@resetPassword');
    Route::post('user/is_show', 'UsersController@changeIsBan');
    Route::resource('user', 'UsersController');
    Route::any('password', 'UsersController@password');

    Route::get('action', 'IndexController@showAction');
    Route::get('index', 'IndexController@index');
    Route::resource('speciality', 'SpecialityController');
    Route::resource('request', 'OrderController');
});



/******* Main page *******/
Route::group([
    'middleware' => 'web'
], function() {
    Route::post('image/upload', 'ImageController@uploadImage');
    Route::post('image/upload/file', 'ImageController@uploadFile');
    Route::get('media/{file_name}', 'ImageController@getImage')->where('file_name', '.*');
    Route::get('file/{file_name}', 'ImageController@showFile')->where('file_name','.*');
});


/******* Index *******/
Route::group([
    'middleware' => 'web',
    'namespace' => 'Index',
], function() {

    Route::get('/', 'IndexController@index');

    Route::get('contact', 'ContactController@showContact');
    Route::get('contact/{url}', 'ContactController@showContact');

    Route::get('faq/{url}', 'FaqController@showFaq');

    Route::get('team', 'TeamController@showTeam');

    Route::get('vacancy', 'VacancyController@showVacancy');
    Route::get('vacancy/{url}', 'VacancyController@showVacancy');

    Route::get('ogpo', 'CalculatorController@showOGPO');
    Route::get('kasko', 'CalculatorController@showKASKO');
    Route::get('kasko-express', 'CalculatorController@showKaskoExpress');

    Route::get('news', 'NewsController@showNewsList')->defaults('type', '/news');
    Route::get('news/{url}', 'NewsController@showNewsById')->defaults('type', '/news');

    Route::get('blog', 'NewsController@showNewsList')->defaults('type', '/blog');
    Route::get('blog/{url}', 'NewsController@showNewsById')->defaults('type', '/blog');

    Route::get('article', 'NewsController@showNewsList')->defaults('type', '/article');
    Route::get('article/{url}', 'NewsController@showNewsById')->defaults('type', '/article');

    Route::get('press-release', 'NewsController@showNewsList')->defaults('type', '/press-release');
    Route::get('press-release/{url}', 'NewsController@showNewsById')->defaults('type', '/press-release');

    Route::get('action', 'OfferController@showOfferList');
    Route::get('action/{url}', 'OfferController@showOfferById');

    Route::get('corporate', 'CorporateController@showCorporate');

    Route::get('documents', 'ReportController@showReport');

    Route::get('insurance', 'InsuranceController@showInsurance');

    Route::get('search', 'IndexController@showSearch');

    Route::get('policy/{hash}/{pdf}', 'PolicyController@showPolicy');

    Route::get('paybox/{hash}/{id}', 'CalculatorController@confirmPay');

    Route::any('paybox-result/{hash}/{id}', 'CalculatorController@confirmPay');


    Route::group([
        'prefix' => 'ajax'
    ], function() {

        Route::post('request', 'IndexController@addRequest');
        Route::post('request/corporate', 'IndexController@addRequestCorporate');
        Route::post('request/vacancy', 'VacancyController@addRequestVacancy');
        Route::post('request/insurance', 'InsuranceController@addRequestInsurance');
        Route::post('request/policy/kasko', 'RequestPolicyController@addRequestPolicy');
        Route::post('request/policy/kasko-express', 'RequestPolicyController@addRequestPolicyKaskoExpress');
        Route::post('get-info-by-iin', 'CalculatorController@getInfoByIIN');
        Route::post('get-car-info', 'CalculatorController@getInfoCar');
        Route::post('calc/agpo', 'CalculatorController@calculateOGPO');
        Route::post('calc/kasko-express', 'CalculatorController@calculateKaskoExpress');
        Route::post('add-driver', 'CalculatorController@addDriver');
        Route::post('add-car', 'CalculatorController@addCar');
        Route::post('reject-policy', 'PolicyController@rejectPolicy');
        Route::post('pay-police', 'CalculatorController@payPolice');
        Route::post('sms', 'AuthController@sendSMS');
        Route::post('vacancy/document', 'VacancyController@getUploadFile');
        Route::post('insurance/document', 'InsuranceController@getUploadFile');
        Route::post('check-validate-policy', 'CalculatorController@checkValidatePolicy');

    });

    Route::group([
        'prefix' => 'auth'
    ], function() {
        Route::get('login', 'AuthController@showLogin');
        Route::get('register', 'AuthController@showRegister');
        Route::get('confirm', 'AuthController@showConfirm');
        Route::get('logout', 'AuthController@logout');
        Route::get('new-phone', 'AuthController@setNewNumber');
        Route::get('reset', 'AuthController@showResetPassword');
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::post('confirm', 'AuthController@confirm');
        Route::post('reset', 'AuthController@sendSmsConfirm');
        Route::get('confirm-reset', 'AuthController@showConfirmResetPassword');
        Route::post('confirm-reset', 'AuthController@confirmResetPassword');
        Route::get('password', 'AuthController@showNewPassword');
        Route::post('password', 'AuthController@setNewPassword');
    });

    Route::group([
        'prefix' => 'profile'
    ], function() {
        Route::get('/', 'ProfileController@showProfile');
        Route::get('policy', 'ProfileController@showPolicy');
        Route::post('/', 'ProfileController@editProfile');
    });

    Route::get('{url}', 'PageController@showPage');

});