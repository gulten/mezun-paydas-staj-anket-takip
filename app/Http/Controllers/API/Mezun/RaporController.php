<?php

namespace App\Http\Controllers\API\Mezun;

use App\BolumeOneriler;
use App\DersGereklilik;
use App\DersKalite;
use App\DigerOneri;
use App\EgitimKalitesi;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rapor\PaydasRequest;
use App\Http\Requests\Rapor\BolumeOneriRequest;
use App\Http\Requests\Rapor\IsDeneyimiRequest;
use App\Http\Requests\Rapor\MezunRequest;
use App\Http\Resources\Mezun as MezunResource;
use App\IsDeneyimi;
use App\PopulerTeknoloji;
use App\YeniDersOneri;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\Permission\Models\Role;

class RaporController extends Controller
{
    public function mezunRapor(MezunRequest $request)
    {
        $request->start_date = $request->start_date . ' 00:00:00';
        $request->end_date = $request->end_date . ' 23:59:59';

        $data = User::role('Mezun')
            ->whereHas('mezun', $mezunFilter = function ($query) use ($request) {
                    $query->whereBetween('mezuniyet_tarihi', [$request->start_date, $request->end_date]);
            })
            ->with(['mezun' => $mezunFilter])
            ->whereHas('ogrenci', $ogrenciFilter = function ($query) use ($request) {
                $query->whereBetween('kayit_yili', [$request->start_date, $request->end_date]);

            if ($request->kayit_sekli)
                $query->whereIn('kayit_sekli', $request->kayit_sekli);

            })
            ->with(['ogrenci' => $ogrenciFilter]);

        $kullanici_bilgileri = $data->get();


        $collection = collect(
            $kullanici_bilgileri
        );
        $ortalama_sure = $collection->avg('mezun.mezuniyet_suresi');

        return ResponseBuilder::success([
            'liste' => $request->field_not_null ? $kullanici_bilgileri->whereNotNull($request->field_not_null)->values()->all() : $kullanici_bilgileri,
            'ortalamasure' => $ortalama_sure
        ]);
    }

    public function paydasRapor(PaydasRequest $request)
    {
        $request->start_date = $request->start_date . ' 00:00:00';
        $request->end_date = $request->end_date . ' 23:59:59';

        $data = User::role('Paydas')
            ->with('paydas');

        $data->whereBetween('created_at', [$request->start_date, $request->end_date]);

        $kullanici_bilgileri = $data->get();

        return ResponseBuilder::success([
            'liste' => $request->field_not_null ? $kullanici_bilgileri->whereNotNull($request->field_not_null)->values()->all() : $kullanici_bilgileri
        ]);
    }

    public function isDeneyimi(IsDeneyimiRequest $request)
    {
        $role = [];
        $request['mezun']?$role=['Mezun']:0;
        $request['paydas']? array_push($role, 'Paydas'):0;

        $request->start_date = $request->start_date . ' 00:00:00';
        $request->end_date = $request->end_date . ' 23:59:59';

        $data = User::role($role)
            ->whereHas('isdeneyimi', $isdeneyimiFilter = function ($query) use ($request) {
                $query->whereBetween('baslama_tarihi', [$request->start_date, $request->end_date]);
                $query->whereBetween('ayrilma_tarihi', [$request->start_date, $request->end_date]);
            })
            ->with(['isdeneyimi' => $isdeneyimiFilter]);


        $data = IsDeneyimi::whereHas('user', $userFilter = function ($query) use ($role) {
                $query->role($role);
            })
            ->with(['user' => $userFilter]);

        $data->whereBetween('baslama_tarihi', [$request->start_date, $request->end_date]);


        if ($request->devam)
            $data->where(function($query) use ($request){
                return $query
                    ->whereBetween('ayrilma_tarihi', [$request->start_date, $request->end_date])
                    ->orWhereNull('ayrilma_tarihi');
            });
        else
            $data->whereBetween('ayrilma_tarihi', [$request->start_date, $request->end_date]);

        $kullanici_bilgileri = $data->get();

        return ResponseBuilder::success([
            'liste' => $request->field_not_null ? $kullanici_bilgileri->whereNotNull($request->field_not_null)->values()->all() : $kullanici_bilgileri,
            'ortalamamaas' => $kullanici_bilgileri->avg('maas')
        ]);
    }

    public function bolumeOneriler(BolumeOneriRequest $request)
    {
        $role = [];
        $request['mezun'] ? $role = ['Mezun'] : 0;
        $request['paydas'] ? array_push($role, 'Paydas') : 0;

        $request->start_date = $request->start_date . ' 00:00:00';
        $request->end_date = $request->end_date . ' 23:59:59';

        $data = BolumeOneriler::whereHas('user', $userFilter = function ($query) use ($role) {
                    $query->role($role);
                })
                ->with(['user' => $userFilter])
                ->whereHas('bolumders', $dersFilter = function ($query) use ($request) {
                    if ($request->donem)
                        $query->where('donem', $request->donem);

                    if ($request->durum)
                        $query->where('durum', $request->durum);

                    if ($request->sinif)
                        $query->where('sinif', $request->sinif);
                })
                ->with(['bolumders' => $dersFilter]);

        $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
        if ($request->ders_id)
            $data->where('ders_id', $request->ders_id);

        $kullanici_bilgileri = $data->get();

        $oneri_liste = ['icerige_yonelik', 'amaca_yonelik', 'ders_yariyilina_saatine_yonelik'];
        foreach ($oneri_liste as $item){
            $chart[$item] = [
                'null' => $kullanici_bilgileri->whereNull($item)->count(),
                'not_null' => $kullanici_bilgileri->whereNotNull($item)->count()
            ];
        }

        return ResponseBuilder::success([
            'liste' => $request->field_not_null?$kullanici_bilgileri->whereNotNull($request->field_not_null)->values()->all():$kullanici_bilgileri,
            'chart' => $chart
            ]);
    }

    public function dersGereklilik(BolumeOneriRequest $request)
    {
        $role = [];
        $request['mezun'] ? $role = ['Mezun'] : 0;
        $request['paydas'] ? array_push($role, 'Paydas') : 0;

        $request->start_date = $request->start_date . ' 00:00:00';
        $request->end_date = $request->end_date . ' 23:59:59';

        $data = DersGereklilik::whereHas('user', $userFilter = function ($query) use ($role) {
            $query->role($role);
        })
            ->with(['user' => $userFilter])
            ->whereHas('bolumders', $dersFilter = function ($query) use ($request) {
                if ($request->donem)
                    $query->where('donem', $request->donem);

                if ($request->durum)
                    $query->where('durum', $request->durum);

                if ($request->sinif)
                    $query->where('sinif', $request->sinif);
            })
            ->with(['bolumders' => $dersFilter]);

        $chart = [];

        $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
        $data->whereBetween('gereklilik', [$request->skor_alt, $request->skor_ust]);

        if ($request->ders_id)
            $data->where('ders_id', $request->ders_id);

        $kullanici_bilgileri = $data->get();

        for ($i = $request->skor_alt; $i <= $request->skor_ust; $i++)
            $chart[] = [$kullanici_bilgileri->where('gereklilik', $i)->count()];

        return ResponseBuilder::success([
            'liste' => $kullanici_bilgileri,
            'chart' => $chart
        ]);
    }

    public function dersKalite(BolumeOneriRequest $request)
    {
        $role = [];
        $request['mezun'] ? $role = ['Mezun'] : 0;
        $request['paydas'] ? array_push($role, 'Paydas') : 0;

        $request->start_date = $request->start_date . ' 00:00:00';
        $request->end_date = $request->end_date . ' 23:59:59';

        $data = DersKalite::whereHas('user', $userFilter = function ($query) use ($role) {
            $query->role($role);
        })
            ->with(['user' => $userFilter])
            ->whereHas('bolumders', $dersFilter = function ($query) use ($request) {
                if ($request->donem)
                    $query->where('donem', $request->donem);

                if ($request->durum)
                    $query->where('durum', $request->durum);

                if ($request->sinif)
                    $query->where('sinif', $request->sinif);
            })
            ->with(['bolumders' => $dersFilter]);

        $chart = [];

        $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
        $data->whereBetween('kalite', [$request->skor_alt, $request->skor_ust]);

        if ($request->ders_id)
            $data->where('ders_id', $request->ders_id);

        $kullanici_bilgileri = $data->get();

        for ($i = $request->skor_alt; $i <= $request->skor_ust; $i++)
            $chart[] = [$kullanici_bilgileri->where('kalite', $i)->count()];

        return ResponseBuilder::success([
            'liste' => $kullanici_bilgileri,
            'chart' => $chart
        ]);
    }

    public function yeniDersOneri(BolumeOneriRequest $request)
    {
        $role = [];
        $request['mezun'] ? $role = ['Mezun'] : 0;
        $request['paydas'] ? array_push($role, 'Paydas') : 0;

        $request->start_date = $request->start_date . ' 00:00:00';
        $request->end_date = $request->end_date . ' 23:59:59';

        $data = YeniDersOneri::whereHas('user', $userFilter = function ($query) use ($role) {
            $query->role($role);
        })
            ->with(['user' => $userFilter]);

        $data->whereBetween('created_at', [$request->start_date, $request->end_date]);

        $kullanici_bilgileri = $data->get();

        return ResponseBuilder::success([
            'liste' => $kullanici_bilgileri
        ]);
    }

    public function populerTeknoloji(BolumeOneriRequest $request)
    {
        $role = [];
        $request['mezun'] ? $role = ['Mezun'] : 0;
        $request['paydas'] ? array_push($role, 'Paydas') : 0;

        $request->start_date = $request->start_date . ' 00:00:00';
        $request->end_date = $request->end_date . ' 23:59:59';

        $data = PopulerTeknoloji::whereHas('user', $userFilter = function ($query) use ($role) {
            $query->role($role);
        })
            ->with(['user' => $userFilter]);

        $data->whereBetween('created_at', [$request->start_date, $request->end_date]);

        $kullanici_bilgileri = $data->get();

        return ResponseBuilder::success([
            'liste' => $kullanici_bilgileri
        ]);
    }

    public function egitimKalite(BolumeOneriRequest $request)
    {
        $role = ['Mezun'];

        $request->start_date = $request->start_date . ' 00:00:00';
        $request->end_date = $request->end_date . ' 23:59:59';

        $data = EgitimKalitesi::whereHas('user', $userFilter = function ($query) use ($role) {
            $query->role($role);
        })
            ->with(['user' => $userFilter]);

        $chart = [];

        $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
        $data->whereBetween('kalite', [$request->skor_alt, $request->skor_ust]);

        $kullanici_bilgileri = $data->get();

        for ($i = $request->skor_alt; $i <= $request->skor_ust; $i++)
            $chart[] = [$kullanici_bilgileri->where('kalite', $i)->count()];

        return ResponseBuilder::success([
            'liste' => $kullanici_bilgileri,
            'chart' => $chart
        ]);
    }

    public function digerOneri(BolumeOneriRequest $request)
    {
        $role = [];
        $request['mezun'] ? $role = ['Mezun'] : 0;
        $request['paydas'] ? array_push($role, 'Paydas') : 0;

        $request->start_date = $request->start_date . ' 00:00:00';
        $request->end_date = $request->end_date . ' 23:59:59';

        $data = DigerOneri::whereHas('user', $userFilter = function ($query) use ($role) {
            $query->role($role);
        })
            ->with(['user' => $userFilter]);

        $data->whereBetween('created_at', [$request->start_date, $request->end_date]);

        $kullanici_bilgileri = $data->get();

        return ResponseBuilder::success([
            'liste' => $kullanici_bilgileri
        ]);
    }
}
