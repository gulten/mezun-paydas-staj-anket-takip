<?php

use App\Komisyon;
use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions

        //Modules
        Permission::create(['name' => 'bbys modülü görüntüle']);
        Permission::create(['name' => 'mezun modülü görüntüle']);
        Permission::create(['name' => 'isyeriegitimi modülü görüntüle']);
        Permission::create(['name' => 'anket modülü görüntüle']);

        //Bbys
        Permission::create(['name' => 'bölüm bilgileri düzenle']);
        Permission::create(['name' => 'öğretim elemanları düzenle']);
        Permission::create(['name' => 'bölüm başkanı düzenle']);
        Permission::create(['name' => 'bölüm başkanı yardımcıları düzenle']);
        Permission::create(['name' => 'komisyonları düzenle']);
        Permission::create(['name' => 'öğrencileri düzenle']);
        Permission::create(['name' => 'mezunları düzenle']);
        Permission::create(['name' => 'paydaşları düzenle']);
        Permission::create(['name' => 'firma yetkilisi düzenle']);
        Permission::create(['name' => 'bölüm derslerini düzenle']);
        Permission::create(['name' => 'mail kontrol']);

        //Mezun
        Permission::create(['name' => 'duyuruları düzenle']);
        Permission::create(['name' => 'mezun raporları']);
        Permission::create(['name' => 'paydaş raporları']);
        Permission::create(['name' => 'iş deneyimi raporları']);
        Permission::create(['name' => 'bölüme öneriler']);
        Permission::create(['name' => 'bölüme öneriler raporları']);
        Permission::create(['name' => 'bölüme katkılar']);
        Permission::create(['name' => 'bölüme katkılar raporları']);

        //Anket
        Permission::create(['name' => 'anketleri düzenle']);
        Permission::create(['name' => 'anketleri raporla']);

        //İsyeriegitimi
        Permission::create(['name' => 'işyeri eğitimi başvuru tarihlerini güncelle']);
        Permission::create(['name' => 'işyeri eğitimi başvuru belgelerini düzenle']);
        Permission::create(['name' => 'işyeri eğitimi başvurusu degerlendirme']);

        Permission::create(['name' => 'işyeri eğitimi mülakat tarihlerini güncelle']);

        Permission::create(['name' => 'işyeri eğitimi teslim tarihlerini güncelle']);
        Permission::create(['name' => 'işyeri eğitimi bitiş belgelerini düzenle']);

        Permission::create(['name' => 'işyeri eğitimi degerlendirme']);

        Permission::create(['name' => 'firma düzenle']);


        // create roles and assign created permissions

        $role = Role::create(['name' => 'Super Admin']);
        $role->givePermissionTo(Permission::all());
        $user = User::find(1)->assignRole('Super Admin');

        $role = Role::create(['name' => 'Bolum Baskani']);
        $role->givePermissionTo(['bbys modülü görüntüle', 'mezun modülü görüntüle', 'anket modülü görüntüle', 'bölüm bilgileri düzenle', 'mail kontrol', 'duyuruları düzenle', 'mezun raporları', 'paydaş raporları', 'iş deneyimi raporları', 'anketleri raporla']);
        $user = User::find(2)->assignRole('Bolum Baskani');

        $role = Role::create(['name' => 'Bolum Baskani Yardimcisi']);
        $role->givePermissionTo(['bbys modülü görüntüle', 'mezun modülü görüntüle', 'anket modülü görüntüle']);


        $role = Role::create(['name' => 'Dekan']);
        $role->givePermissionTo(['bbys modülü görüntüle', 'mezun modülü görüntüle', 'anket modülü görüntüle', 'anketleri raporla']);

        $role = Role::create(['name' => 'Bolum Sistem Sorumlusu']);

        $role = Role::create(['name' => 'Staj Denetcisi']);
        $role = Role::create(['name' => 'Staj Firma Sorumlusu']);

        $role = Role::create(['name' => 'Işyeri Eğitimi Denetcisi']);
        $role = Role::create(['name' => 'İsyeri Egitimi Firma Sorumlusu']);

        $role = Role::create(['name' => 'Ogrenci']);
        $role->givePermissionTo(['isyeriegitimi modülü görüntüle']);
        $user = User::find(3)->assignRole('Ogrenci');


        $role = Role::create(['name' => 'Mezun']);
        $role->givePermissionTo(['mezun modülü görüntüle', 'bölüme öneriler']);

        $role = Role::create(['name' => 'Paydas']);
        $role->givePermissionTo(['mezun modülü görüntüle', 'bölüme öneriler']);

        $role = Role::create(['name' => 'Firma Yetkilisi']);
        $role->givePermissionTo(['isyeriegitimi modülü görüntüle']);


        //komisyonlar--->
        $role = Role::create(['name' => 'Isyeri Egitimi Komisyon Baskani']);
        $komisyon = Komisyon::create(['role_id' => $role['id']]);
        $role->givePermissionTo(['isyeriegitimi modülü görüntüle', 'firma düzenle', 'firma yetkilisi düzenle', 'işyeri eğitimi başvuru belgelerini düzenle', 'işyeri eğitimi başvurusu degerlendirme', 'işyeri eğitimi bitiş belgelerini düzenle', 'işyeri eğitimi başvuru tarihlerini güncelle', 'işyeri eğitimi mülakat tarihlerini güncelle', 'işyeri eğitimi teslim tarihlerini güncelle', 'işyeri eğitimi degerlendirme']);


        $role = Role::create(['name' => 'Isyeri Egitimi Komisyon Uyesi']);
        $komisyon = Komisyon::create(['role_id' => $role['id']]);
        $role->givePermissionTo(['isyeriegitimi modülü görüntüle', 'firma düzenle', 'firma yetkilisi düzenle', 'işyeri eğitimi başvuru belgelerini düzenle', 'işyeri eğitimi başvurusu degerlendirme', 'işyeri eğitimi bitiş belgelerini düzenle', 'işyeri eğitimi başvuru tarihlerini güncelle', 'işyeri eğitimi mülakat tarihlerini güncelle', 'işyeri eğitimi teslim tarihlerini güncelle', 'işyeri eğitimi degerlendirme']);

        $role = Role::create(['name' => 'Staj Komisyon Baskani']);
        $komisyon = Komisyon::create(['role_id' => $role['id']]);
        $role->givePermissionTo(['anketleri düzenle']);
        $role->givePermissionTo(['anketleri raporla']);

        $role = Role::create(['name' => 'Staj Komisyon Uyesi']);
        $komisyon = Komisyon::create(['role_id' => $role['id']]);
        $role->givePermissionTo(['anketleri düzenle']);
        $role->givePermissionTo(['anketleri raporla']);

        //komisyonlar son<---





    }
}
