<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\ApartmentTypeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CarServiceController;
use App\Http\Controllers\ExtraServiceController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\DestinationTypeController;
// use App\Http\Middleware\SetLanguage;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// //////////////////////////////////////////////////////
// ADD PAGINATE FOR ALL DO NOT FORGET
// get all tags for specific type  ex. programs tags
// /////////////////////////////////////////////////////////
// Apartments routes
Route::prefix('apartments')->group(function () {
    Route::get('/for-sell', [ApartmentController::class, 'getForSell']);//just edit the response
    Route::get('/for-rent', [ApartmentController::class, 'getForRent']);//just edit the response
    Route::get('/type/{typeId}', [ApartmentController::class, 'getByType']); //just edit the response
    Route::get('/{id}', [ApartmentController::class, 'getApartment']);//just edit the response
    Route::post('/add', [ApartmentController::class, 'addApartment']);//just edit the response
    Route::delete('/{id}', [ApartmentController::class, 'deleteApartment']);//just edit the response
    Route::put('/{id}', [ApartmentController::class, 'editApartment']);//check code---------
});

// Apartment types routes
Route::prefix('apartment-types')->group(function () {
    Route::get('/', [ApartmentTypeController::class, 'getAllTypes']);//just edit the response
    Route::get('/{typeId}', [ApartmentTypeController::class, 'getType']);//just edit the response
    Route::post('/add', [ApartmentTypeController::class, 'addType']);
    Route::delete('/{typeId}', [ApartmentTypeController::class, 'deleteType']);
    Route::put('/{typeId}', [ApartmentTypeController::class, 'editType']);
});

Route::prefix('cities')->group(function (){
    Route::get('/',[CityController::class, 'index']);
    Route::get('/{id}',[CityController::class,'getCity']);
    Route::post('/add',[CityController::class,'addCity']);
    Route::delete('/{id}',[CityController::class,'deleteCity']);
    Route::put('/{id}',[CityController::class,'editCity']); //check this it is wrong
});  // just edit the response


Route::prefix('areas')->group(function (){
    Route::get('/popular/{cityId}',[AreaController::class,'getAreaPopular']);
    Route::get('/',[AreaController::class,'index']);
    Route::get('/{id}',[AreaController::class,'getArea']);
    Route::get('/in_city/{cityId}',[AreaController::class,'getByCity']);
    Route::post('/add',[AreaController::class,'addArea']);
    Route::delete('/{id}',[AreaController::class,'deleteArea']);
    Route::put('/{id}',[AreaController::class,'editArea']);
}); // edit the response

Route::prefix('programs')->group(function (){
    Route::get('/',[ProgramController::class,'index']);
    Route::get('/in_city/{cityId}',[ProgramController::class,'getByCity']);
    Route::get('/in_area/{areaId}',[ProgramController::class,'getByArea']);
    Route::get('/tag/{tagId}',[ProgramController::class,'getByTag']);
    Route::get('/{id}',[ProgramController::class,'getProgram']);
    Route::post('/add',[ProgramController::class,'addProgram']);
    Route::delete('/{id}',[ProgramController::class,'deleteProgram']);
    Route::put('/{id}',[ProgramController::class,'editProgram']);
    Route::get('/in_city_and_destination_type/{cityId}/{destinationTypeId}', [ProgramController::class, 'getByCityAndDestinationType']);
});

Route::prefix('tags')->group(function (){
    Route::get('/',[TagController::class,'index']);
    Route::get('/{id}',[TagController::class,'getTag']);
    Route::post('/add',[TagController::class,'addTag']);
    Route::put('/{id}',[TagController::class,'editTag']);
    Route::delete('/{id}',[TagController::class,'deleteTag']);
});

Route::prefix('activities')->group(function (){
    Route::get('/',[ActivityController::class,"index"]);
    Route::get('/{id}',[ActivityController::class,"getActivity"]);
    Route::get('/in_city/{cityId}',[ActivityController::class,'getByCity']);
    Route::get('/in_area/{areaId}',[ActivityController::class,'getByArea']);
    Route::get('/tag/{tagId}',[ActivityController::class,'getByTag']);
    Route::post('/add',[ActivityController::class,"addActivity"]);
    Route::put('/{id}',[ActivityController::class,"editActivity"]);
    Route::delete('/{id}',[ActivityController::class,"deleteActivity"]);
});

Route::prefix('car_services')->group(function (){
    Route::get('/',[CarServiceController::class,"index"]);
    Route::get('/{id}',[CarServiceController::class,"getCarService"]);
    Route::post('/add',[CarServiceController::class,"addCarService"]);
    Route::put('/{id}',[CarServiceController::class,"editCarService"]);
    Route::delete('/{id}',[CarServiceController::class,"deleteCarService"]);
});



// this apis need to make the data not able to repeat like the title , and edit the response and check the edit function
Route::prefix('extra-services')->group(function (){
    Route::get('/',[ExtraServiceController::class,'index']);//error
    Route::get('/{slug}',[ExtraServiceController::class,'getExtraServiceBySlug']);
    Route::get('/{id}',[ExtraServiceController::class,'getExtraService']);
    Route::post('/add',[ExtraServiceController::class,'addExtraService']);
    Route::put('/{id}',[ExtraServiceController::class,'editExtraService']);//error
    Route::delete('/{id}',[ExtraServiceController::class,'deleteExtraService']);
});


Route::prefix('packages')->group(function (){
    Route::get('/',[PackageController::class,'index']);
    Route::get('/{id}',[PackageController::class,'getPackage']);
    Route::get('/in_city/{cityId}',[PackageController::class,'getByCity']);
    Route::get('/in_area/{areaId}',[PackageController::class,'getByArea']);
    Route::get('/tag/{tagId}',[PackageController::class,'getByTag']);
    Route::post('/add',[PackageController::class,'addPackage']);
    Route::get('/duration/{number}',[PackageController::class,'getByDuration']);
    Route::get('/activity/{activity_id}',[PackageController::class,'getByActivity']);
    Route::delete('/{id}',[PackageController::class,"deletePackage"]);
});

Route::prefix('hotels')->group(function (){
    Route::get('/',[HotelController::class,'index']);
    Route::get('/{id}',[HotelController::class,'getHotel']);
    Route::post('/add',[HotelController::class,'addHotel']);
    Route::get('/in_city/{cityId}',[HotelController::class,'getByCity']);

});
Route::prefix('restaurants')->group(function (){
    Route::get('/',[RestaurantController::class,'index']);
    Route::get('/{id}',[RestaurantController::class,'getRestaurant']);
    Route::post('/add',[RestaurantController::class,'addRestaurant']);
    Route::get('/in_city/{cityId}',[RestaurantController::class,'getByCity']);

});

Route::prefix('destinations')->group(function (){
    Route::get('/',[DestinationTypeController::class,'index']);
    Route::get('/{id}',[DestinationTypeController::class,'getDestinationType']);
    Route::post('/add',[DestinationTypeController::class,'addDestinationType']);
    Route::get('/in_city/{cityId}/{destinationTypeId}',[DestinationTypeController::class,'getByCityAndDestinationType']);

});
