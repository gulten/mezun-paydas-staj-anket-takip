<?php

use App\BolumBilgileri;
use Illuminate\Foundation\Console\Presets\React;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/*\Illuminate\Support\Facades\DB::listen(function ($sql) {
    info($sql->sql, [
        'bindings' => $sql->bindings,
        'time'     => $sql->time,
    ]);
});*/

Route::post('login', 'UserController@login');

Route::post('forgot', 'Auth\ForgotPasswordController@sendPasswordResetLink')->name('password.email');
Route::post('reset/password', 'Auth\ResetPasswordController@reset');
Route::post('reset', 'UserController@reset');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', 'UserController@logout');

    Route::prefix('roles')->group(function (){
        Route::get('user', function () {
            return ResponseBuilder::success(['roles' => Auth::user()->roles()->pluck('name')]);
        });
    });

    Route::prefix('permissions')->group(function () {

        Route::group(['middleware' => ['check_role']], function () {
            Route::get('user', function (Request $request) {
                $role = Role::findByName($request->header('Role'), 'web');
                return ResponseBuilder::success(['permissions' => $role->getAllPermissions()]);
            });
        });

    });


    //BBYS ROUTES START
    Route::prefix('bbys')->group(function (){

        Route::group(['middleware' => ['check_role']], function () {
            Route::get('/left-menu', 'Bbys\IzinlerController@leftMenu');
        });

        Route::group(['middleware' => ['role:Super Admin']], function () {
            Route::get('/all_permissions', 'Bbys\IzinlerController@allPermissions');
            Route::get('/all_roles', 'Bbys\IzinlerController@allRoles');
            Route::resource('izinler', 'Bbys\IzinlerController');
            Route::resource('roller', 'Bbys\RollerController');

        });

        Route::group(['middleware' => ['role:Super Admin']], function () {
            Route::put('kullanicilar/{id}/toggle-status', 'Bbys\KullanicilarController@toggleStatus');
            Route::put('kullanicilar/{id}/change-password', 'Bbys\KullanicilarController@changePassword');
            Route::resource('kullanicilar', 'Bbys\KullanicilarController');
            Route::post('post', 'DuyuruController@store');//
        });

        Route::group(['middleware' => ['check_role', 'permission:mail kontrol']], function () {
            Route::get('/send_mail/{id}', 'Bbys\MailController@send_mail');
        });

        Route::group(['middleware' => ['check_role','permission:bölüm bilgileri düzenle']], function () {
            Route::resource('bolum-bilgileri', 'Bbys\BolumController');
        });

        Route::group(['middleware' => ['check_role','permission:öğretim elemanları düzenle']], function () {
            Route::resource('ogretim-elemanlari', 'Bbys\OgretimElemaniController');
        });

        Route::group(['middleware' => ['check_role','permission:bölüm başkanı düzenle']], function () {
            Route::resource('bolum-baskani', 'Bbys\BolumBaskaniController');
        });

        Route::group(['middleware' => ['check_role','permission:bölüm başkanı yardımcıları düzenle']], function () {
            Route::resource('bolum-baskani-yardimcilari', 'Bbys\BolumBaskaniYardimcisiController');
        });

        /*Route::group(['middleware' => ['check_role','permission:komisyonları düzenle']], function () {
            Route::get('komisyonlar/all_roles', 'Bbys\KomisyonController@allRoles');
            Route::resource('komisyonlar', 'Bbys\KomisyonController');
        });*/

        Route::group(['middleware' => ['check_role','permission:öğrencileri düzenle']], function () {
            Route::resource('ogrenciler', 'Bbys\OgrenciController');
        });

        Route::group(['middleware' => ['check_role','permission:mezunları düzenle']], function () {
            Route::resource('mezunlar', 'Bbys\MezunController');
        });

        Route::group(['middleware' => ['check_role','permission:paydaşları düzenle']], function () {
            Route::resource('paydaslar', 'Bbys\PaydasController');
        });

        Route::group(['middleware' => ['check_role', 'permission:firma düzenle']], function () {
            Route::get('/firmalar/ara', 'Bbys\FirmaController@filter');
            Route::resource('firma', 'Bbys\FirmaController');
        });

        Route::group(['middleware' => ['check_role', 'permission:firma yetkilisi düzenle']], function () {
            Route::resource('firma_yetkilisi', 'Bbys\FirmaYetkilisiController');
        });

        Route::group(['middleware' => ['check_role','permission:bölüm derslerini düzenle']], function () {
            Route::resource('bolum-dersleri', 'Bbys\BolumDersleriController');
        });

        Route::group(['middleware' => ['check_role', 'permission:mail kontrol']], function () {
            Route::resource('mail-taslaklari', 'Bbys\MailDraftController');
            Route::put('/secret_mail/{id}', 'Bbys\MailController@secret_mail');
            Route::post('mail-gonder/UserList', 'Bbys\KullanicilarController@getRoleHasKullanici');
        });


    });
    //BBYS ROUTES END

    //MEZUN ROUTES START
    Route::prefix('mezun')->group(function (){
        Route::group(['middleware' => ['check_role']], function () {
            Route::get('/left-menu', 'Mezun\IzinlerController@leftMenu');
        });

        Route::group(['middleware' => ['check_role','role:Mezun']], function () {
            Route::put('mezun-profil/change-password', 'Mezun\MezunController@changePassword');
            Route::resource('mezun-profil', 'Mezun\MezunController');
            Route::resource('mezun-isdeneyimi', 'Mezun\IsDeneyimiController');
            Route::resource('mezun-anket', 'Mezun\AnketController');
        });

        Route::group(['middleware' => ['check_role', 'role:Paydas']], function () {
            Route::put('paydas-profil/change-password', 'Mezun\PaydasController@changePassword');
            Route::resource('paydas-profil', 'Mezun\PaydasController');
            Route::resource('paydas-isdeneyimi', 'Mezun\IsDeneyimiController');
        });

        Route::group(['middleware' => ['check_role', 'permission:mezun raporları']], function () {
            Route::post('mezun/rapor', 'Mezun\RaporController@mezunRapor');
        });

        Route::group(['middleware' => ['check_role', 'permission:paydaş raporları']], function () {
            Route::post('paydas/rapor', 'Mezun\RaporController@paydasRapor');
        });

        Route::group(['middleware' => ['check_role', 'permission:iş deneyimi raporları']], function () {
            Route::post('isdeneyimi/rapor', 'Mezun\RaporController@isDeneyimi');
        });

        Route::group(['middleware' => ['check_role', 'permission:bölüme öneriler raporları']], function () {
            Route::post('bolume-oneriler/rapor', 'Mezun\RaporController@bolumeOneriler');
            Route::post('ders-gereklilik/rapor', 'Mezun\RaporController@dersGereklilik');
            Route::post('ders-kalite/rapor', 'Mezun\RaporController@dersKalite');
            Route::post('yeniders-oneri/rapor', 'Mezun\RaporController@yeniDersOneri');
            Route::post('populer-teknoloji/rapor', 'Mezun\RaporController@populerTeknoloji');
            Route::post('egitim-kalite/rapor', 'Mezun\RaporController@egitimKalite');
            Route::post('bolum-diger-oneri/rapor', 'Mezun\RaporController@digerOneri');
            Route::get('rapor/tum-dersler', 'Mezun\BolumDersleriController@index');
        });

        Route::group(['middleware' => ['check_role', 'role:Paydas|Mezun', 'permission:bölüme öneriler']], function () {
            Route::resource('bolume-oneriler', 'Mezun\BolumeOnerilerController');
            Route::resource('ders-gereklilik', 'Mezun\DersGereklilikController');
            Route::resource('ders-kalite', 'Mezun\DersKaliteController');
            Route::resource('yeniders-oneri', 'Mezun\YeniDersOneriController');
            Route::resource('populer-teknoloji', 'Mezun\PopulerTeknolojiController');

            Route::group(['middleware' => ['role:Mezun']], function () {
                Route::resource('egitim-kalite', 'Mezun\EgitimKalitesiController');
            });

            Route::resource('bolum-diger-oneri', 'Mezun\DigerOneriController');

            Route::get('tum-dersler', 'Mezun\BolumDersleriController@index');
            Route::get('dersler-oneriler', 'Mezun\BolumDersleriController@getDerslerWithoutOneri');
        });

    });
    //MEZUN ROUTES END

    //ANKET ROUTES START
    Route::prefix('anket')->group(function () {
        Route::group(['middleware' => ['check_role']], function () {
            Route::get('/left-menu', 'Anket\IzinlerController@leftMenu');
        });
        Route::group(['middleware' => ['check_role', 'permission:anketleri düzenle']], function () {
            Route::resource('/sorular', 'Anket\AnketController');
        });
        Route::group(['middleware' => ['check_role', 'permission:anketleri raporla']], function () {
            Route::get('/rapor/list', 'Anket\RaporController@List');
            Route::get('/rapor/{id}', 'Anket\RaporController@AnketUser');
            Route::get('/rapor-cevap/{id}', 'Anket\RaporController@AnketCevap');
            Route::get('/rapor-chart/{id}', 'Anket\RaporController@ViewChart');
            Route::get('/rapor-table/{id}', 'Anket\RaporController@ViewTable');
        });

    });
    //ANKET ROUTES END

    //İŞYERİ EĞİTİMİ ROUTES START
    Route::prefix('isyeriegitimi')->group(function () {
        Route::group(['middleware' => ['check_role']], function () {

            Route::get('/left-menu', 'IsyeriEgitimi\IzinlerController@leftMenu');

            Route::group(['middleware' => ['permission:işyeri eğitimi başvuru tarihlerini güncelle']], function () {
                Route::resource('/basvuru-tarihleri/guncelle', 'IsyeriEgitimi\BasvuruTarihleriController');
            });

            Route::group(['middleware' => ['permission:işyeri eğitimi mülakat tarihlerini güncelle']], function () {
                Route::resource('/mulakat-tarihleri/guncelle', 'IsyeriEgitimi\MulakatTarihleriController');
            });

            Route::group(['middleware' => ['permission:işyeri eğitimi teslim tarihlerini güncelle']], function () {
                Route::resource('/teslim-tarihleri/guncelle', 'IsyeriEgitimi\TeslimTarihleriController');
            });

            Route::group(['middleware' => ['permission:işyeri eğitimi başvuru belgelerini düzenle']], function () {
                Route::put('/basvuru-belgeleri/duzenle/{id}/toggle-status', 'IsyeriEgitimi\BasvuruBelgeleriController@toggleStatus');
                Route::resource('/basvuru-belgeleri/duzenle', 'IsyeriEgitimi\BasvuruBelgeleriController');
            });

            Route::group(['middleware' => ['permission:işyeri eğitimi başvurusu degerlendirme']], function () {
                Route::put('/basvuru-sureci/belgeler/{id}/toggle-documents', 'IsyeriEgitimi\BasvuruYonetimController@toggleDocuments');
                Route::resource('/basvuru-sureci/belgeler', 'IsyeriEgitimi\BasvuruYonetimController');
                Route::resource('/bitis-sureci/belgeler', 'IsyeriEgitimi\BitisYonetimController');
                Route::put('/bitis-sureci/belgeler/{id}/toggle-documents', 'IsyeriEgitimi\BitisYonetimController@toggleDocuments');
                Route::get('/bitis-sureci/firmayetkilileri/{id}', 'IsyeriEgitimi\FirmaYetkilisiController@search');
                Route::resource('/basvuru-sureci/yonetim', 'IsyeriEgitimi\BasvuruYonetimController');

            });

            Route::group(['middleware' => ['permission:işyeri eğitimi bitiş belgelerini düzenle']], function () {
                Route::put('/bitis-belgeleri/duzenle/{id}/toggle-status', 'IsyeriEgitimi\BitisBelgeleriController@toggleStatus');
                Route::resource('/bitis-belgeleri/duzenle', 'IsyeriEgitimi\BitisBelgeleriController');
            });

            Route::group(['middleware' => ['permission:işyeri eğitimi degerlendirme']], function () {
                Route::resource('/degerlendirme/yonetim', 'IsyeriEgitimi\DegerlendirmeYonetimController');
                Route::resource('/durum/liste', 'IsyeriEgitimi\DurumListeController');
            });

            Route::group(['middleware' => ['check_role', 'role:Ogrenci']], function () {
                Route::get('/basvuru-belgeleri/goruntule', 'IsyeriEgitimi\BasvuruBelgeleriController@listBelgeler');
                Route::get('/basvuru/firmalar', 'IsyeriEgitimi\FirmaController@filter');
                Route::get('/islem-tarihleri', 'IsyeriEgitimi\IslemTarihleriController@listTarih');
                Route::get('/basvuru-tarihleri', 'IsyeriEgitimi\BasvuruTarihleriController@listTarih');
                Route::get('/mulakat-tarihleri', 'IsyeriEgitimi\MulakatTarihleriController@listTarih');
                Route::get('/teslim-tarihleri', 'IsyeriEgitimi\TeslimTarihleriController@listTarih');
                Route::get('/basvuru/toplam', 'IsyeriEgitimi\BasvurularController@sumDays');
                Route::resource('/basvuru', 'IsyeriEgitimi\BasvurularController');
                Route::get('/bitis-belgeleri/goruntule', 'IsyeriEgitimi\BitisBelgeleriController@listBelgeler');
                Route::get('/bitis-anketi/ogrenci/cevaplar', 'IsyeriEgitimi\OgrenciAnketController@userAnswers');
                Route::resource('/bitis-anketi/ogrenci', 'IsyeriEgitimi\OgrenciAnketController');
            });

            Route::group(['middleware' => ['check_role', 'role:Firma Yetkilisi']], function () {
                Route::get('/bitis-anketi/ogrenci-listesi', 'IsyeriEgitimi\YetkiliAnketController@ogrenciList');
                Route::get('/bitis-anketi/firma/{id}/cevaplar', 'IsyeriEgitimi\YetkiliAnketController@userAnswers');
                Route::resource('/bitis-anketi/firma', 'IsyeriEgitimi\YetkiliAnketController');
            });

        });

    });
    //İŞYERİ EĞİTİMİ ROUTES END

    //LEFT MENU ROUTES START
    Route::get('/left-menu/visitor', function () {
        $menu = [];

        array_push($menu, [
            'icon' => 'home',
            'text' => 'Gösterge Paneli',
            'route' => 'dashboard'
        ]);

        return $menu;
    });
    //LEFT MENU ROUTES END
});
