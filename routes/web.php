<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SigninController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AddCarController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserinfoController;
use App\Http\Controllers\AdminphotoController;
use App\Http\Controllers\AdmininfoController;
use App\Http\Controllers\EmailRequestController;
use App\Http\Controllers\BookingformsController;
use App\Http\Controllers\RatingsController;
use App\Http\Controllers\NotificationController;


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

Route::post('/create_account_client', [SigninController::class, 'create_account_client']);
Route::post('/checklogin', [LoginController::class,'checklogin']);
Route::post('/adminchecklogin', [AdmininfoController::class,'adminchecklogin']);


Route::get('/dd', function () {
    return view('main.email-template');
});


Route::get('/test-whatsapp', function () {
    $data = [
        'name' => 'Test User',
        'car_details' => (object) ['name' => 'Toyota Corolla'],
        'start_date' => '2024-10-24',
        'start_time' => '10:00 AM',
        'return_date' => '2024-10-30',
        'return_time' => '10:00 AM',
        'total_amount_payable' => '100.00',
    ];

    $whatsappNumber = 'whatsapp:+639463588376'; // Replace with actual number
    (new BookingformsController)->sendWhatsAppMessage($whatsappNumber, $data);
});




// Dashboard Routes

Route::middleware(['preventBackHistory'])->group(function () {

    Route::middleware(['guest'])->group(function () {
        Route::get('/dashboard-login', [AdmininfoController::class,'loginroute'])->name('dashboard_login');
    });

    Route::middleware(['auth','admin','verified','verified.redirect'])->group(function () {

        Route::get('/dashboard', [AdmininfoController::class,'dashboardroute']);

        // RENTED ROUTES
        Route::get('/rented', [AddCarController::class,'db_rentedcars']);
        Route::get('/rented/{id}/ajaxview', [AddCarController::class,'db_rented_ajaxview']);

        Route::get('/available', [AddCarController::class,'db_availablecars']);
        Route::get('/all-vehicles', [AddCarController::class,'db_allvehicles']);
        Route::get('/settings', [AdmininfoController::class,'admin_account_settings_route']);

        // ADMIN ROUTES
        Route::put('/adminpp_update', [AdmininfoController::class,'adminpp_update']);
        Route::put('/admininfo_update', [AdmininfoController::class,'admininfo_update']);
        Route::put('/adminpassword_update', [AdmininfoController::class,'adminpassword_update']);

        // USER MANAGEMENT
        Route::get('/user/management', [AdmininfoController::class,'user_management_route']);
        Route::post('/create-user-role', [AdmininfoController::class,'create_user_role']);
        Route::get('/view-user-role/{id}/ajaxview', [AdmininfoController::class,'db_dashboard_users_ajaxview']);
        Route::get('/delete_db_user/{id}', [AdmininfoController::class,'delete_db_user']);
        Route::get('/edit-user-role/{id}/ajaxedit', [AdmininfoController::class, 'db_dashboard_users_ajaxedit']);
        Route::put('/update_db_user', [AdmininfoController::class, 'update_db_user']);


        // CAR ROUTES
        Route::get('/add', [AddCarController::class,'addcar_route']);
        Route::post('/addcar', [AddCarController::class,'save']);
        Route::get('/delete_car/{id}', [AddCarController::class,'delete_car'])->name('delete_car');
        Route::get('/viewcar/{slug}', [AddCarController::class,'db_viewvehicle'])->name('dashboard.viewcar');
        Route::get('/editcar/{slug}', [AddCarController::class,'db_editcar']);
        Route::put('/updatecar/{slug}', [AddCarController::class,'db_updatecar']);

        // INQUIRY ROUTES
        Route::get('/inquiry', [EmailRequestController::class,'db_inquiry']);
        Route::get('/delete_inquiry/{id}', [EmailRequestController::class,'db_inquiry_delete']);
        Route::get('/inquiry/{id}/ajaxview', [EmailRequestController::class,'db_inquiry_ajaxview']);

        // BOOKINGS ROUTES
        Route::get('/bookings', [BookingformsController::class,'db_bookings']);
        Route::get('/delete_booking/{id}', [BookingformsController::class,'db_booking_delete']);
        Route::get('/bookings/{id}/ajaxview', [BookingformsController::class,'db_booking_ajaxview']);
        Route::post('/confirm_booking/{id}', [BookingformsController::class, 'confirmBooking'])->name('confirm.booking');
        Route::patch('/decline_booking/{id}', [BookingformsController::class, 'declineBooking']);
   
        // CLIENTS ROUTES
        Route::get('/allusers', [UserinfoController::class,'db_allusers']);
        Route::get('/delete_user/{id}', [UserinfoController::class,'db_user_delete']);
        // Route::get('/delete_account/{id}', [UserinfoController::class,'delete_user']);
        Route::get('/user/{id}/ajaxview', [UserinfoController::class,'db_user_ajaxview']);

        // RATINGS ROUTES
        Route::get('/allratings', [RatingsController::class,'db_ratings_route']);
        Route::get('/view_ratings/{id}/ajaxview', [RatingsController::class,'db_ratings_ajaxview']);
        Route::get('/delete_ratings/{id}', [RatingsController::class,'db_ratings_delete']);

        // SALES
        Route::get('/sales_report', [AdmininfoController::class,'db_route_sales']);

        // NOTIFICATIONS
        Route::get('/notification', [NotificationController::class,'db_notification']);
        Route::put('/mark_as_read_admin/{id}', [NotificationController::class, 'markNotificationAsRead_admin']);
        Route::get('/delete_admin_notification/{id}', [NotificationController::class,'deleteNotification_admin']);        
        
    });



    Route::get('/adminlogout', [AdmininfoController::class,'adminlogout']);
});






    Route::get('/guest-home', [AddCarController::class,'guest_allcars']);
    Route::get('/guestviewcar/{slug}', [AddCarController::class,'guest_viewvehicle']);
    Route::get('/guest-van', [AddCarController::class,'guest_van']);
    Route::get('/guest-7seaters', [AddCarController::class,'guest_7seaters']);
    Route::get('/guest-pickup', [AddCarController::class,'guest_pickup']);
    Route::get('/guest-hatchback', [AddCarController::class,'guest_hatchback']);
    Route::get('/guest-sedan', [AddCarController::class,'guest_sedan']);
    Route::get('/cars/guest-search', [AddCarController::class,'guest_search_rental'])->name('car.home-search');

    Route::post('/email', [EmailRequestController::class, 'sendEmail'])->name('send.email');


// Main Routes



    Route::get('/mainviewcar/{slug}', [AddCarController::class,'main_viewvehicle']);

    // Route::get('home', [AddCarController::class,'main_allcars']);
    Route::get('/home', [AddCarController::class, 'main_allcars'])->name('home');

    Route::get('/van', [AddCarController::class,'main_van']);
    Route::get('/7seaters', [AddCarController::class,'main_7seaters']);
    Route::get('/pickup', [AddCarController::class,'main_pickup']);
    Route::get('/hatchback', [AddCarController::class,'main_hatchback']);
    Route::get('/sedan', [AddCarController::class,'main_sedan']);
    Route::get('/cars/search', [AddCarController::class,'main_search_rental'])->name('car.search');

    // BOOKINGS
    Route::get('/bookingforms/{slug}', [BookingformsController::class,'booking_route']);
    Route::get('/weekly_bookingforms/{slug}', [BookingformsController::class,'weekly_booking_route']);
    Route::get('/monthly_bookingforms/{slug}', [BookingformsController::class,'monthly_booking_route']);
    Route::post('/bookingformsubmit/{slug}', [BookingformsController::class,'booking_submit'])->name('book.submit');
    Route::post('/weekly_bookingformsubmit/{slug}', [BookingformsController::class,'weekly_booking_submit'])->name('weeklybook.submit');
    Route::post('/monthly_bookingformsubmit/{slug}', [BookingformsController::class,'monthly_booking_submit'])->name('monthlybook.submit');
    Route::get('/success-booking', [BookingformsController::class, 'succes_booking_route']);       






Route::get('/', function () {return view('home.homepage');});





// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();   
Auth::routes(['verify' => true]);

Route::get('email/verify', function () {
    return view('auth.verify'); // Make sure this path points to your actual Blade file
})->middleware('auth')->name('auth.verify');


Route::get('/welcome', function () {
    return view('home.welcome');
});


