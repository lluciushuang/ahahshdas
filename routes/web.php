    <?php

    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\FirstController;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\StoreController;
    use Illuminate\Support\Facades\Route;

    // --- Public Routes (Bisa diakses siapa saja) ---
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/hi', function(){
        return 'hi';
    })->name('hi');

    Route::get('/helo', function(){
        return 'helooo isb';
    })->name('helo');

    Route::get('/about', function(){
        return view('about');
    })->name('about');


    // --- Auth & Guest Routes ---
    Route::get('/login', [AuthController::class, 'show_login'])->name('login.show')->middleware('guest');
    Route::post('/login.auth', [AuthController::class, 'login_auth'])->name('login.auth');


    // --- Protected Routes (Harus Login / Middleware Auth) ---
    Route::middleware('auth')->group(function() {
        
        // Home Routes
        Route::get('/home', [HomeController::class, 'show'])->name('home');
        Route::get('/home/sum', [FirstController::class, 'sum'])->name('home.sum');
        Route::get('/home/multiply/{param1}/{param2?}', [FirstController::class, 'multiply'])->name('home.multiply');

        // --- Role: Admin & Owner Only ---
        Route::middleware(['role:admin,owner'])->group(function() {
            Route::get('/product/insert-form', [StoreController::class, 'product_insert_form'])->name('product_insert_form');
            Route::post('/product/insert', [StoreController::class, 'insert_product'])->name('insert_product');
            
            Route::get('/product/edit/{product_id}', [StoreController::class, 'product_edit_form'])->name('product_edit_form');
            Route::put('/product/edit/{product_id}', [StoreController::class, 'update_product'])->name('update_product');
            Route::delete('/product/delete/{product_id}', [StoreController::class, 'delete_product'])->name('delete_product');
        });

        // --- Role: Customer, Admin, & Owner ---
        Route::middleware(['role:customer,admin,owner'])->group(function() {
            Route::get('/store', [StoreController::class, 'show'])->name('store');
        });

        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });