<?php

namespace App\Traits;

use App\User;
use Illuminate\Support\Collection;

trait RoleControl
{
    public static function getImportantRoles(){
        return [
                'Super Admin',
                'Bolum Baskani',
                'Dekan',
                'Bolum Baskani Yardimcisi',
                'Bolum Sistem Sorumlusu',
                'Staj Denetcisi',
                'Staj Firma Sorumlusu',
                'Işyeri Eğitimi Denetcisi',
                'İsyeri Egitimi Firma Sorumlusu',
                'Ogrenci',
                'Mezun',
                'Paydas'
            ];
    }
    public static function getImportantRolesWithName()
    {
        return [
            ['id' => 1, 'name' => 'Super Admin'],
            ['id' => 2, 'name' => 'Bolum Baskani'],
            ['id' => 3, 'name' => 'Bolum Baskani Yardimcisi'],
            ['id' => 4, 'name' => 'Dekan'],
            ['id' => 5, 'name' => 'Bolum Sistem Sorumlusu'],
            ['id' => 6, 'name' => 'Staj Denetcisi'],
            ['id' => 7, 'name' => 'Staj Firma Sorumlusu'],
            ['id' => 8, 'name' => 'Işyeri Eğitimi Denetcisi'],
            ['id' => 9, 'name' => 'İsyeri Egitimi Firma Sorumlusu'],
            ['id' => 10, 'name' => 'Ogrenci'],
            ['id' => 11, 'name' => 'Mezun'],
            ['id' => 12, 'name' => 'Paydas']
        ];
    }
    public static function OneRolesControl(Collection $getRoles){
        $hasOneRoles = ['Bolum Baskani', 'Dekan'];

        $filteredRoles = $getRoles
            ->where('name', '!=', 'Super Admin');

        foreach ($hasOneRoles as $key=>$role) {
            if (($getRoles
                    ->where('name', $role)
                    ->count() > 0) &&
                (User::role($role)->count() > 0)
            ) {
                $filteredRoles = $filteredRoles
                    ->where('name', '!=', $role);
            }
        }
        return $filteredRoles->map(function ($item) {
            return ['name' => $item['name']];
        });
    }
    public static function RoleConflictControl(Collection $getRoles){

        $role[0] = 'Ogrenci';
        $role[1] = 'Mezun';

        $filteredRoles = $getRoles;
        if ( ($getRoles
            ->where('name', $role[0])
            ->count()>0 ) &&
            ($getRoles
                ->where('name', $role[1])
                ->count()>0)
        )
            $filteredRoles = $getRoles
                ->where('name', '!=', $role[0]);

        return $filteredRoles->map(function ($item) {
            return ['name' => $item['name']];
        });
    }
};
