<?php

namespace App\Http\Controllers\API\IsyeriEgitimi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            'route' => '/isyeriegitimi/dashboard'
        ]);

        if ($role->hasPermissionTo('işyeri eğitimi başvuru tarihlerini güncelle'))
            array_push($menu, [
                'icon' => 'file-document-box-multiple-outline',
                'text' => 'Başvuru Tarihlerini Güncelle',
                'route' => '/isyeriegitimi/basvuru-tarihleri/guncelle'
            ]);

        if ($role->hasPermissionTo('işyeri eğitimi başvuru belgelerini düzenle'))
            array_push($menu, [
                'icon' => 'file-document-box-multiple-outline',
                'text' => 'Başvuru Belgelerini Düzenle',
                'route' => '/isyeriegitimi/basvuru-belgeleri/duzenle'
            ]);

        if ($role->hasPermissionTo('işyeri eğitimi teslim tarihlerini güncelle'))
            array_push($menu, [
                'icon' => 'file-document-box-multiple-outline',
                'text' => 'Bitiş Belge Teslim Tarihlerini Güncelle',
                'route' => '/isyeriegitimi/teslim-tarihleri/guncelle'
            ]);

        if ($role->hasPermissionTo('işyeri eğitimi mülakat tarihlerini güncelle'))
            array_push($menu, [
                'icon' => 'file-document-box-multiple-outline',
                'text' => 'Mülakat Tarihlerini Güncelle',
                'route' => '/isyeriegitimi/mulakat-tarihleri/guncelle'
            ]);

        if ($role->hasPermissionTo('işyeri eğitimi bitiş belgelerini düzenle'))
            array_push($menu, [
                'icon' => 'file-document-box-multiple-outline',
                'text' => 'Bitiş Belgelerini Düzenle',
                'route' => '/isyeriegitimi/bitis-belgeleri/duzenle'
            ]);

        if ($role->hasPermissionTo('işyeri eğitimi başvurusu degerlendirme')) {
            array_push($menu, [
                'icon' => 'book-open-page-variant',
                'text' => 'Başvuru Belgelerini Değerlendirme',
                'route' => '/isyeriegitimi/basvuru-sureci/belgeler/teslim',
            ]);
        }

        if ($role->hasPermissionTo('işyeri eğitimi degerlendirme')) {
            array_push($menu, [
                'icon' => 'book-open-page-variant',
                'text' => 'Bitiş Belgelerini Değerlendirme',
                'route' => '/isyeriegitimi/bitis-sureci/belgeler/teslim',
            ]);
        }

        if ($role->hasPermissionTo('işyeri eğitimi degerlendirme')) {
            array_push($menu, [
                'icon' => 'file-document-box-multiple-outline',
                'text' => 'Mülakat Notlarının Girişi',
                'route' => '/isyeriegitimi/degerlendirme/yonetim'
            ]);
            array_push($menu, [
                'icon' => 'file-document-box-multiple-outline',
                'text' => 'İşyeri Eğitimi Durumları',
                'route' => '/isyeriegitimi/durum/liste'
            ]);
        }


        if (($role->name === 'Firma Yetkilisi')) {
            array_push($menu, [
                'icon' => 'card-bulleted',
                'text' => 'Firma Bitiş Anketi Öğrenci Listesi',
                'route' => '/isyeriegitimi/bitis-anketi/ogrenci-listesi'
            ]);
        }
        if (($role->name === 'Ogrenci')) {
            array_push($menu, [
                'icon' => 'information-outline',
                'text' => 'İşyeri Eğitimi Başvuru Süreci Hakkında Bilgi',
                'route' => '/isyeriegitimi/basvuru-sureci/bilgi',
            ]);
            array_push($menu, [
                'icon' => 'file-download',
                'text' => 'Başvuru Belgelerini Görüntüle',
                'route' => '/isyeriegitimi/basvuru-belgeleri/goruntule'
            ]);

            array_push($menu, [
                'icon' => 'folder-information-outline',
                'text' => 'İşyeri Eğitimine Başlamadan Önceki İşlemler',
                'route' => '/isyeriegitimi/baslangic/belge/kontrol/',
            ]);
            array_push($menu, [
                'icon' => 'file-download',
                'text' => 'Bitiş Belgelerini Görüntüle',
                'route' => '/isyeriegitimi/bitis-belgeleri/goruntule'
            ]);
            array_push($menu, [
                'icon' => 'card-bulleted',
                'text' => 'Öğrenci Bitiş Anketi',
                'route' => '/isyeriegitimi/bitis-anketi/ogrenci'
            ]);
            array_push($menu, [
                'icon' => 'briefcase-plus-outline',
                'text' => 'İşyeri Eğitimi Başvuru',
                'route' => '/isyeriegitimi/basvuru-sureci/basvuru',
            ]);
        }

        return $menu;
    }
}
