<?php

namespace App\Http\Controllers\API\Bbys;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Izinler\StoreRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\Izin as IzinResource;
use Spatie\Permission\Models\Role;

class IzinlerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $izinler = Role::all();
        return ResponseBuilder::success(['roller' => IzinResource::collection($izinler)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(StoreRequest $request, $id)
    {
        $role = Role::find($id);

        if ($role->name === 'Super Admin')
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {

            $role->syncPermissions($request->permissions);
            DB::commit();
            return ResponseBuilder::success();

        } catch (\Exception $e) {
            DB::rollback();

            return ResponseBuilder::error();
        }
        return ResponseBuilder::success();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function allPermissions()
    {
        $izinler = Permission::all()->pluck('name');
        return ResponseBuilder::success(['izinler' => $izinler]);
    }
    public function allRoles()
    {
        $izinler = Role::all('name');
        return ResponseBuilder::success(['roller' => $izinler]);
    }

    public function leftMenu(Request $request){
        $role = Role::findByName($request->header('Role'), 'web');

        $user = Auth::user();

        $menu = [];

        array_push($menu, [
            'icon'=>'home',
            'text'=> 'Gösterge Paneli',
            'route'=> '/bbys/dashboard'
        ]);


        if ($role->hasPermissionTo('bölüm bilgileri düzenle'))
            array_push($menu, [
                'icon'=>'file-document-box-multiple-outline',
                'text'=> 'Bölüm Bilgileri',
                'route'=> '/bbys/bolum-bilgileri'
            ]);


        if ($role->hasPermissionTo('öğretim elemanları düzenle'))
            array_push($menu, [
                'icon'=>'account-badge-horizontal',
                'text'=> 'Öğretim Elemanları',
                'route'=> '/bbys/ogretim-elemanlari/list'
            ]);


        if ($role->hasPermissionTo('bölüm başkanı düzenle'))
            array_push($menu, [
                'icon'=>'account-tie',
                'text'=> 'Bölüm Başkanı Bilgileri',
                'route'=> '/bbys/bolum-baskani-bilgileri'
            ]);


        if ($role->hasPermissionTo('bölüm başkanı yardımcıları düzenle'))
            array_push($menu, [
                'icon'=>'account-group',
                'text'=> 'Bölüm Başkanı Yardımcıları Bilgileri',
                'route'=> '/bbys/bolum-baskani-yardimcilari/list'
            ]);

/*
        if ($role->hasPermissionTo('komisyonları düzenle'))
            array_push($menu, [
                'icon'=>'account-switch',
                'text'=> 'Komisyon Bilgileri',
                'route'=> '/bbys/komisyonlar/list'
            ]);
*/

        if ($role->hasPermissionTo('öğrencileri düzenle'))
            array_push($menu, [
                'icon'=>'account-card-details',
                'text'=> 'Öğrenci Bilgileri',
                'route'=> '/bbys/ogrenciler/list'
            ]);


        if ($role->hasPermissionTo('mezunları düzenle'))
            array_push($menu, [
                'icon'=>'account-remove-outline',
                'text'=> 'Mezun Bilgileri',
                'route'=> '/bbys/mezunlar/list'
            ]);


        if ($role->hasPermissionTo('paydaşları düzenle'))
            array_push($menu, [
                'icon'=>'account-multiple-plus-outline',
                'text'=> 'Paydas Bilgileri',
                'route'=> '/bbys/paydaslar/list'
            ]);

        if ($role->hasPermissionTo('firma düzenle'))
        array_push($menu, [
            'icon' => 'briefcase-account-outline',
            'text' => 'Firma Bilgileri',
            'route' => '/bbys/firma/list'
        ]);

        if ($role->hasPermissionTo('firma yetkilisi düzenle'))
            array_push($menu, [
                'icon' => 'briefcase-account-outline',
                'text' => 'Firma Yetkilisi Bilgileri',
                'route' => '/bbys/firma_yetkilisi/list'
            ]);

        if ($role->hasPermissionTo('bölüm derslerini düzenle'))
            array_push($menu, [
                'icon'=>'book-open-page-variant',
                'text'=> 'Bölüm Dersleri',
                'route'=> '/bbys/bolum-dersleri/list'
            ]);


        if ($role->hasPermissionTo('mail kontrol')) {
            array_push($menu, [
                'icon' => 'email-newsletter',
                'text' => 'Mail Taslakları',
                'route' => '/bbys/mail-taslaklari/list'
            ]);

            array_push($menu, [
                'icon' => 'email-send',
                'text' => 'Mail Gönder',
                'route' => '/bbys/mail-gonder/list'
            ]);

        }


        if ($user->hasRole('Super Admin'))
            array_push($menu, [
                'icon' => 'account-key',
                'text' => 'Rolleri Yönet',
                'route' => '/bbys/roller/list'
            ]);

        if ($user->hasRole('Super Admin'))
            array_push($menu, [
                'icon'=>'lock-alert',
                'text'=> 'İzinleri Yönet',
                'route'=> '/bbys/izinler/list'
            ]);

        if ($user->hasRole('Super Admin'))
            array_push($menu, [
                'icon'=>'account-lock',
                'text'=> 'Kullanıcıları Yönet',
                'route'=> '/bbys/kullanicilar/list'
            ]);

        return $menu;
    }
}
