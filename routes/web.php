<?php

use App\Models\AgeForm;
use Illuminate\Support\Facades\Route;
use App\Models\Member;
use App\Models\MemberForm;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

Route::redirect('/', 'admin');

Route::get('member', function() {
    dd(Member::find(1)?->getMemberBenefitsAmount());
});