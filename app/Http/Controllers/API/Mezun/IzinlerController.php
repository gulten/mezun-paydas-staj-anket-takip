<?php

namespace App\Http\Controllers\API\Mezun;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class IzinlerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function leftMenu(Request $request){
        $role = Role::findByName($request->header('Role'), 'web');

        $user = Auth::user();

        $menu = [];

        array_push($menu, [
            'icon'=>'home',
            'text'=> 'Gösterge Paneli',
            'route'=> '/mezun/dashboard'
        ]);


        if ($role->name==='Mezun')
            array_push($menu, [
                'icon'=>'account-tie',
                'text'=> 'Kişisel Bilgiler',
                'route'=> '/mezun/mezun-kisisel-bilgiler'
        ]);

        if ($role->name==='Mezun')
            array_push($menu, [
                'icon'=>'briefcase-plus',
                'text'=> 'İşyeri Bilgileri',
                'route'=> '/mezun/mezun-isdeneyimi'
        ]);

        if ($role->name === 'Mezun')
            array_push($menu, [
                'icon' => 'briefcase-plus',
                'text' => 'Anket',
                'route' => '/mezun/mezun-anket'
            ]);

        if ($role->name === 'Paydas')
            array_push($menu, [
                'icon' => 'account-tie',
                'text' => 'Kişisel Bilgiler',
                'route' => '/mezun/paydas-kisisel-bilgiler'
            ]);

        if ($role->name === 'Paydas')
            array_push($menu, [
                'icon' => 'briefcase-plus',
                'text' => 'İşyeri Bilgileri',
                'route' => '/mezun/paydas-isdeneyimi'
            ]);

        if (($role->name === 'Mezun'))
            array_push($menu, [
                'icon' => 'briefcase-plus',
                'text' => 'Bölüme Öneriler',
                'route' => '/mezun/bolume-oneriler',
                'sub_menu' => true,
                'group' => [
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Dersin Amacına ve İçeriğine Yönelik',
                        'route' => '/mezun/bolume-oneriler/ders',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Dersin Gerekliliğe Yönelik',
                        'route' => '/mezun/bolume-oneriler/gereklilik',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Dersin İşleniş Kalitesine Yönelik',
                        'route' => '/mezun/bolume-oneriler/kalite',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Yeni Ders Önerisi',
                        'route' => '/mezun/bolume-oneriler/yeniders',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Popüler Teknolojiler',
                        'route' => '/mezun/bolume-oneriler/populerteknoloji',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Eğitim Kalitesi Değerlendirmesi',
                        'route' => '/mezun/bolume-oneriler/egitimkalite',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Diğer Öneriler',
                        'route' => '/mezun/bolume-oneriler/diger',
                    ],
                ]
            ]);

        if (($role->name === 'Paydas'))
            array_push($menu, [
                'icon' => 'briefcase-plus',
                'text' => 'Bölüme Öneriler',
                'route' => '/mezun/bolume-oneriler',
                'sub_menu' => true,
                'group' => [
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Dersin Amacına ve İçeriğine Yönelik',
                        'route' => '/mezun/bolume-oneriler/ders',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Dersin Gerekliliğe Yönelik',
                        'route' => '/mezun/bolume-oneriler/gereklilik',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Dersin İşleniş Kalitesine Yönelik',
                        'route' => '/mezun/bolume-oneriler/kalite',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Yeni Ders Önerisi',
                        'route' => '/mezun/bolume-oneriler/yeniders',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Popüler Teknolojiler',
                        'route' => '/mezun/bolume-oneriler/populerteknoloji',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Diğer Öneriler',
                        'route' => '/mezun/bolume-oneriler/diger',
                    ],
                ]
            ]);

        if ($role->hasPermissionTo('mezun raporları'))
            array_push($menu, [
                'icon' => 'book-open-page-variant',
                'text' => 'Mezun Raporları',
                'route' => '/mezun/mezun/rapor/'
            ]);

        if ($role->hasPermissionTo('paydaş raporları'))
            array_push($menu, [
                'icon' => 'book-open-page-variant',
                'text' => 'Paydaş Raporları',
                'route' => '/mezun/paydas/rapor/'
            ]);

        if ($role->hasPermissionTo('iş deneyimi raporları'))
            array_push($menu, [
                'icon' => 'book-open-page-variant',
                'text' => 'İş Deneyimi Raporları',
                'route' => '/mezun/isdeneyimi/rapor/'
            ]);

        if ($role->hasPermissionTo('bölüme öneriler raporları'))
            array_push($menu, [
                'icon' => 'book-open-page-variant',
                'text' => 'Bölüme Öneriler Raporları',
                'route' => '/mezun/bolume-oneriler/rapor/',
                'sub_menu' => true,
                'group' => [
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Dersin Amacına ve İçeriğine Yönelik',
                        'route' => '/mezun/bolume-oneriler/rapor',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Dersin Gerekliliğe Yönelik',
                        'route' => '/mezun/bolume-oneriler/gereklilik/rapor',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Dersin İşleniş Kalitesine Yönelik',
                        'route' => '/mezun/bolume-oneriler/kalite/rapor',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Yeni Ders Önerisi',
                        'route' => '/mezun/bolume-oneriler/yeniders/rapor',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Popüler Teknolojiler',
                        'route' => '/mezun/bolume-oneriler/populerteknoloji/rapor',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Eğitim Kalitesi Değerlendirmesi',
                        'route' => '/mezun/bolume-oneriler/egitimkalite/rapor',
                    ],
                    [
                        'icon' => 'book-open-page-variant',
                        'text' => 'Diğer Öneriler',
                        'route' => '/mezun/bolume-oneriler/diger/rapor',
                    ],
                ]
        ]);

        return $menu;
    }
}
