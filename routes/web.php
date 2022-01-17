<?php

use App\Models\User;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\{
    ItemController,
    UnitController,
    TypeController,
    SupplierController
};
use App\Http\Controllers\Privileges\{
    GuestController,
    UserController,
    RoleController,
    NavigationController,
    RoleHasPermissionController,
    UserHasPermissionController
};
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Transaction\{IncomingItemController, OutgoingItemController};
use Illuminate\Support\Facades\{Route, Auth};

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['middleware' => 'prevent.back.history'], function () {
    Route::fallback(function() {
        return abort(404);
    });

    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

    Auth::routes(['reset' => false, 'verify' => false, 'password.request' => false, 'register' => false]);

    Route::group(['middleware' => ['auth', 'has.role']], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('', [ProfileController::class, 'index'])->name('index');
            Route::post('store-about-you', [ProfileController::class, 'storeAboutYou'])->name('store.about.you');
            Route::post('store-image', [ProfileController::class, 'storeImage'])->name('store.image');
            Route::put('update-image', [ProfileController::class, 'updateImage'])->name('update.image');
            Route::put('update-about-you', [ProfileController::class, 'updateAboutYou'])->name('update.about.you');
            Route::put('{slug}', [ProfileController::class, 'update'])->name('update');
        });

        Route::group(['prefix' => 'setting', 'as' => 'setting.', 'middleware' => 'permission:setting'], function () {
            Route::get('', [SettingController::class, 'index'])->name('index');
            Route::get('table', [SettingController::class, 'table'])->name('table');
            Route::post('{id}/edit', [SettingController::class, 'edit'])->name('edit');
            Route::put('{id}', [SettingController::class, 'update'])->name('update');
        });

        Route::group(['prefix' => 'laporan', 'as' => 'laporan.', 'middleware' => 'permission:laporan'], function () {
            Route::get('', [ReportController::class, 'index'])->name('index');
            Route::get('print', [ReportController::class, 'print'])->name('print');
            Route::post('create', [ReportController::class, 'create'])->name('create');




            // Route::group(['middleware' => 'permission:laporan-create'], function () {

            //     Route::post('store', [ReportController::class, 'store'])->name('store');
            // });
            // Route::group(['middleware' => ['permission:laporan-edit', 'permission:laporan-roles']], function () {
            //     Route::group(['middleware' => 'permission:laporan-edit'], function () {
            //         Route::post('{id}/edit', [ReportController::class, 'edit'])->name('edit');
            //     });
            //     Route::put('{id}', [ReportController::class, 'update'])->name('update');
            // });
            // Route::group(['middleware' => 'permission:laporan-delete'], function () {
            //     Route::delete('{id}', [ReportController::class, 'destroy'])->name('destroy');
            // });
        });

        // ---------------------------------------------------------------

        Route::group(['prefix' => 'barang-masuk', 'as' => 'barang-masuk.', 'middleware' => 'permission:barang-masuk'], function () {
            Route::get('', [IncomingItemController::class, 'index'])->name('index');
            Route::get('table', [IncomingItemController::class, 'table'])->name('table');
            Route::group(['middleware' => 'permission:barang-masuk-create'], function () {
                Route::post('create', [IncomingItemController::class, 'create'])->name('create');
                Route::post('store', [IncomingItemController::class, 'store'])->name('store');
            });
            Route::group(['middleware' => ['permission:barang-masuk-edit', 'permission:barang-masuk-roles']], function () {
                Route::group(['middleware' => 'permission:barang-masuk-edit'], function () {
                    Route::post('{id}/edit', [IncomingItemController::class, 'edit'])->name('edit');
                });
                Route::put('{id}', [IncomingItemController::class, 'update'])->name('update');
            });
            Route::group(['middleware' => 'permission:barang-masuk-delete'], function () {
                Route::delete('{id}', [IncomingItemController::class, 'destroy'])->name('destroy');
            });
        });

        Route::group(['prefix' => 'barang-keluar', 'as' => 'barang-keluar.', 'middleware' => 'permission:barang-keluar'], function () {
            Route::get('', [OutgoingItemController::class, 'index'])->name('index');
            Route::get('table', [OutgoingItemController::class, 'table'])->name('table');
            Route::group(['middleware' => 'permission:barang-keluar-create'], function () {
                Route::post('create', [OutgoingItemController::class, 'create'])->name('create');
                Route::post('store', [OutgoingItemController::class, 'store'])->name('store');
            });
            Route::group(['middleware' => ['permission:barang-keluar-edit', 'permission:barang-keluar-roles']], function () {
                Route::group(['middleware' => 'permission:barang-keluar-edit'], function () {
                    Route::post('{id}/edit', [OutgoingItemController::class, 'edit'])->name('edit');
                });
                Route::put('{id}', [OutgoingItemController::class, 'update'])->name('update');
            });
            Route::group(['middleware' => 'permission:barang-keluar-delete'], function () {
                Route::delete('{id}', [OutgoingItemController::class, 'destroy'])->name('destroy');
            });
        });

        // ---------------------------------------------------------------

        Route::group(['prefix' => 'supplier', 'as' => 'supplier.', 'middleware' => 'permission:supplier'], function () {
            Route::get('', [SupplierController::class, 'index'])->name('index');
            Route::get('table', [SupplierController::class, 'table'])->name('table');
            Route::group(['middleware' => 'permission:supplier-create'], function () {
                Route::post('create', [SupplierController::class, 'create'])->name('create');
                Route::post('store', [SupplierController::class, 'store'])->name('store');
            });
            Route::group(['middleware' => ['permission:supplier-edit', 'permission:supplier-roles']], function () {
                Route::group(['middleware' => 'permission:supplier-edit'], function () {
                    Route::post('{id}/edit', [SupplierController::class, 'edit'])->name('edit');
                });
                Route::put('{id}', [SupplierController::class, 'update'])->name('update');
            });
            Route::group(['middleware' => 'permission:supplier-delete'], function () {
                Route::delete('{id}', [SupplierController::class, 'destroy'])->name('destroy');
            });
        });

        Route::group(['prefix' => 'barang', 'as' => 'barang.', 'middleware' => 'permission:barang'], function () {
            Route::get('', [ItemController::class, 'index'])->name('index');
            Route::get('table', [ItemController::class, 'table'])->name('table');
            Route::group(['middleware' => 'permission:barang-create'], function () {
                Route::post('create', [ItemController::class, 'create'])->name('create');
                Route::post('store', [ItemController::class, 'store'])->name('store');
            });
            Route::group(['middleware' => ['permission:barang-edit', 'permission:barang-roles']], function () {
                Route::group(['middleware' => 'permission:barang-edit'], function () {
                    Route::post('{id}/edit', [ItemController::class, 'edit'])->name('edit');
                });
                Route::put('{id}', [ItemController::class, 'update'])->name('update');
            });
            Route::group(['middleware' => 'permission:barang-delete'], function () {
                Route::delete('{id}', [ItemController::class, 'destroy'])->name('destroy');
            });
        });

        Route::group(['prefix' => 'satuan', 'as' => 'satuan.', 'middleware' => 'permission:satuan'], function () {
            Route::get('', [UnitController::class, 'index'])->name('index');
            Route::get('table', [UnitController::class, 'table'])->name('table');
            Route::group(['middleware' => 'permission:satuan-create'], function () {
                Route::post('create', [UnitController::class, 'create'])->name('create');
                Route::post('store', [UnitController::class, 'store'])->name('store');
            });
            Route::group(['middleware' => ['permission:satuan-edit', 'permission:satuan-roles']], function () {
                Route::group(['middleware' => 'permission:satuan-edit'], function () {
                    Route::post('{id}/edit', [UnitController::class, 'edit'])->name('edit');
                });
                Route::put('{id}', [UnitController::class, 'update'])->name('update');
            });
            Route::group(['middleware' => 'permission:satuan-delete'], function () {
                Route::delete('{id}', [UnitController::class, 'destroy'])->name('destroy');
            });
        });

        Route::group(['prefix' => 'jenis', 'as' => 'jenis.', 'middleware' => 'permission:jenis'], function () {
            Route::get('', [TypeController::class, 'index'])->name('index');
            Route::get('table', [TypeController::class, 'table'])->name('table');
            Route::group(['middleware' => 'permission:jenis-create'], function () {
                Route::post('create', [TypeController::class, 'create'])->name('create');
                Route::post('store', [TypeController::class, 'store'])->name('store');
            });
            Route::group(['middleware' => ['permission:jenis-edit', 'permission:jenis-roles']], function () {
                Route::group(['middleware' => 'permission:jenis-edit'], function () {
                    Route::post('{id}/edit', [TypeController::class, 'edit'])->name('edit');
                });
                Route::put('{id}', [TypeController::class, 'update'])->name('update');
            });
            Route::group(['middleware' => 'permission:jenis-delete'], function () {
                Route::delete('{id}', [TypeController::class, 'destroy'])->name('destroy');
            });
        });

        // ---------------------------------------------------------------

        Route::group(['prefix' => 'guest', 'as' => 'guest.', 'middleware' => 'permission:guest'], function () {
            Route::get('', [GuestController::class, 'index'])->name('index');
            Route::get('table', [GuestController::class, 'table'])->name('table');
            Route::group(['middleware' => 'permission:guest-create'], function () {
                Route::post('create', [GuestController::class, 'create'])->name('create');
                Route::post('store', [GuestController::class, 'store'])->name('store');
            });
            Route::group(['middleware' => ['permission:guest-edit', 'permission:guest-roles']], function () {
                Route::group(['middleware' => 'permission:guest-edit'], function () {
                    Route::post('{id}/edit', [GuestController::class, 'edit'])->name('edit');
                });
                Route::group(['middleware' => 'permission:guest-roles'], function () {
                    Route::post('{id}/roles', [GuestController::class, 'roles'])->name('roles');
                });
                Route::put('{id}/{slug}', [GuestController::class, 'update'])->name('update');
            });
            Route::group(['middleware' => 'permission:guest-delete'], function () {
                Route::delete('{id}', [GuestController::class, 'destroy'])->name('destroy');
            });
        });

        Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => 'permission:user'], function () {
            Route::get('', [UserController::class, 'index'])->name('index');
            Route::get('table', [UserController::class, 'table'])->name('table');
            Route::group(['middleware' => 'permission:user-create'], function () {
                Route::post('create', [UserController::class, 'create'])->name('create');
                Route::post('store', [UserController::class, 'store'])->name('store');
            });
            Route::group(['middleware' => 'permission:user-edit'], function () {
                Route::post('{id}/edit', [UserController::class, 'edit'])->name('edit');
                Route::put('{id}', [UserController::class, 'update'])->name('update');
            });
            Route::group(['middleware' => 'permission:user-delete'], function () {
                Route::delete('{id}', [UserController::class, 'destroy'])->name('destroy');
            });
        });

        Route::group(['prefix' => 'role', 'as' => 'role.', 'middleware' => 'permission:role'], function () {
            Route::get('', [RoleController::class, 'index'])->name('index');
            Route::get('table', [RoleController::class, 'table'])->name('table');
            Route::group(['middleware' => 'permission:role-create'], function () {
                Route::post('create', [RoleController::class, 'create'])->name('create');
                Route::post('store', [RoleController::class, 'store'])->name('store');
            });
            Route::group(['middleware' => 'permission:role-edit'], function () {
                Route::post('{id}/edit', [RoleController::class, 'edit'])->name('edit');
                Route::put('{id}', [RoleController::class, 'update'])->name('update');
            });
            Route::group(['middleware' => 'permission:role-delete'], function () {
                Route::delete('{id}', [RoleController::class, 'destroy'])->name('destroy');
            });
        });

        Route::group(['prefix' => 'role-has-permission', 'as' => 'role-has-permission.', 'middleware' => 'permission:role-has-permission'], function () {
            Route::get('', [RoleHasPermissionController::class, 'index'])->name('index');
            Route::get('table', [RoleHasPermissionController::class, 'table'])->name('table');

            Route::group(['middleware' => 'permission:role-has-permission-create'], function () {
                Route::post('create', [RoleHasPermissionController::class, 'create'])->name('create');
                Route::post('store', [RoleHasPermissionController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'permission:role-has-permission-edit'], function () {
                Route::post('{id}/{slug}', [RoleHasPermissionController::class, 'show'])->name('show');
                Route::put('{id}/{slug}', [RoleHasPermissionController::class, 'update'])->name('update');
            });
        });

        Route::group(['prefix' => 'user-has-permission', 'as' => 'user-has-permission.', 'middleware' => 'permission:user-has-permission'], function () {
            Route::get('', [UserHasPermissionController::class, 'index'])->name('index');
            Route::get('table', [UserHasPermissionController::class, 'table'])->name('table');

            Route::group(['middleware' => 'permission:user-has-permission-create'], function () {
                Route::post('create', [UserHasPermissionController::class, 'create'])->name('create');
                Route::post('store', [UserHasPermissionController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'permission:user-has-permission-edit'], function () {
                Route::post('{id}/{slug}', [UserHasPermissionController::class, 'show'])->name('show');
                Route::put('{id}/{slug}', [UserHasPermissionController::class, 'update'])->name('update');
            });
        });

        Route::group(['prefix' => 'navigation', 'as' => 'navigation.', 'middleware' => 'permission:navigation'], function () {
            Route::get('', [NavigationController::class, 'index'])->name('index');
            Route::get('table', [NavigationController::class, 'table'])->name('table');
            Route::get('{id}/table', [NavigationController::class, 'tableAction'])->name('table.action');

            Route::post('', [NavigationController::class, 'store'])->name('store');
            Route::post('{id}/action', [NavigationController::class, 'storeAction'])->name('store.action');

            Route::post('{id}', [NavigationController::class, 'show'])->name('show');

            Route::put('{id}/update', [NavigationController::class, 'update'])->name('update');

            Route::delete('{id}', [NavigationController::class, 'destroy'])->name('destroy');
            Route::delete('{id}/action', [NavigationController::class, 'destroyAction'])->name('destroy.action');
        });
    });

});
