<?php

namespace App\Http\Controllers\API\Anket;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class IzinlerController extends Controller
{
    public function leftMenu(Request $request)
    {
        $role = Role::findByName($request->header('Role'), 'web');

        $user = Auth::user();

        $menu = [];

        array_push($menu, [
            'icon' => 'home',
            'text' => 'Gösterge Paneli',
            'route' => '/anket/dashboard'
        ]);

        if ($role->hasPermissionTo('anketleri düzenle')) {
            array_push($menu, [
                'icon' => 'account-star',
                'text' => 'Mezun Anketi',
                'route' => '/anket/mezun-anketi/'
            ]);

            array_push($menu, [
                'icon' => 'account-tie',
                'text' => 'Akademik Personel Anketi',
                'route' => '/anket/akademik-personel-anketi/'
            ]);

            array_push($menu, [
                'icon' => 'account-details',
                'text' => 'Öğrenci Memnuniyet Anketi',
                'route' => '/anket/ogrenci-memnuniyet-anketi/'
            ]);

            array_push($menu, [
                'icon' => 'account-details-outline',
                'text' => 'Personel Memnuniyet Anketi',
                'route' => '/anket/personel-memnuniyet-anketi/'
            ]);

            array_push($menu, [
                'icon' => 'account-alert',
                'text' => 'Yeni Gelen Öğrenci Anketi',
                'route' => '/anket/yenigelen-ogrenci-anketi/'
            ]);

            array_push($menu, [
                'icon' => 'account-child',
                'text' => 'İşyeri Eğitimi Öğrenci Anketi',
                'route' => '/anket/isyeriegitimi-ogrenci-anketi/'
            ]);

            array_push($menu, [
                'icon' => 'account-group',
                'text' => 'İşyeri Eğitimi İşyeri Anketi',
                'route' => '/anket/isyeriegitimi-isyeri-anketi/'
            ]);
        }

        if ($role->hasPermissionTo('anketleri raporla')) {
            array_push($menu, [
                'icon' => 'account-star',
                'text' => 'Anket Raporları',
                'route' => '/anket/anket-raporlari/'
            ]);
        }


        return $menu;
    }
}
