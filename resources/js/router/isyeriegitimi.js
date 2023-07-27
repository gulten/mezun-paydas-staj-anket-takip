import store from '../store';

export default {
    path: '/isyeriegitimi',
    component: () => import(`../isyeriegitimi/container/IsyeriEgitimi`),
    redirect: '/isyeriegitimi/dashboard',
    children: [
        {
            path: '/isyeriegitimi/dashboard',
            meta: {
                name: 'İşyeri Eğitimi Dashboard View',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/dashboard/Dashboard`),
            name: 'İşyeri Eğitimi Dashboard'
        },
        {
            path: '/isyeriegitimi/basvuru-belgeleri/duzenle',
            meta: {
                name: 'Başvuru Belgelerini Düzenle',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/basvuru/BelgeDuzenle`),
            name: 'Başvuru Belgelerini Düzenle'
        },
        {
            path: '/isyeriegitimi/basvuru-belgeleri/goruntule',
            meta: {
                name: 'Başvuru Belgelerini Goruntule',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/basvuru/BelgeGoruntule`),
            name: 'Başvuru Belgelerini Goruntule'
        },
        {
            path: '/isyeriegitimi/basvuru-sureci/bilgi',
            meta: {
                name: 'İşyerine Başvuru Bilgi',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/basvuru/Bilgi`),
            name: 'İşyerine Başvuru Bilgi'
        },
        {
            path: '/isyeriegitimi/basvuru-sureci/basvuru',
            meta: {
                name: 'İşyerine Başvuru',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/basvuru/Basvuru`),
            name: 'İşyerine Başvuru'
        },
        {
            path: '/isyeriegitimi/baslangic/belge/kontrol/',
            meta: {
                name: 'Başlangıç Belge Kontrol',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/baslangic/Baslangic`),
            name: 'Başlangıç Belge Kontrol'
        },
        {
            path: '/isyeriegitimi/bitis-belgeleri/duzenle',
            meta: {
                name: 'Bitiş Belgelerini Düzenle',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/bitis/BelgeDuzenle`),
            name: 'Bitiş Belgelerini Düzenle'
        },
        {
            path: '/isyeriegitimi/bitis-belgeleri/goruntule',
            meta: {
                name: 'Bitiş Belgelerini Goruntule',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/bitis/BelgeGoruntule`),
            name: 'Bitiş Belgelerini Goruntule'
        },
        {
            path: '/isyeriegitimi/bitis-anketi/ogrenci',
            meta: {
                name: 'Öğrenci Bitiş Anketi',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/bitis/OgrenciBitisAnketi`),
            name: 'Öğrenci Bitiş Anketi'
        },
        {
            path: '/isyeriegitimi/bitis-anketi/ogrenci/cevaplar',
            meta: {
                name: 'Öğrenci Bitiş Anketi Cevaplar',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/bitis/OgrenciBitisAnketiCevaplar`),
            name: 'Öğrenci Bitiş Anketi Cevaplar'
        },
        {
            path: '/isyeriegitimi/basvuru-tarihleri/guncelle',
            meta: {
                name: 'Başvuru Tarihleri Güncelle',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/basvuru/BasvuruTarihleriGuncelle`),
            name: 'Başvuru Tarihleri Güncelle'
        },
        {
            path: '/isyeriegitimi/mulakat-tarihleri/guncelle',
            meta: {
                name: 'Mülakat Tarihleri Güncelle',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/bitis/MulakatTarihleriGuncelle`),
            name: 'Mülakat Tarihleri Güncelle'
        },
        {
            path: '/isyeriegitimi/teslim-tarihleri/guncelle',
            meta: {
                name: 'Teslim Tarihleri Güncelle',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/bitis/TeslimTarihleriGuncelle`),
            name: 'Teslim Tarihleri Güncelle'
        },

        {
            path: '/isyeriegitimi/basvuru-sureci/belgeler/teslim',
            meta: {
                name: 'Başlangıç Belge Teslim',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/basvuru/BelgeTeslim`),
            name: 'Başlangıç Belge Teslim'
        },
        {
            path: '/isyeriegitimi/bitis-sureci/belgeler/teslim',
            meta: {
                name: 'Bitiş Belge Teslim',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/bitis/BelgeTeslim`),
            name: 'Bitiş Belge Teslim'
        },
        {
            path: '/isyeriegitimi/degerlendirme/yonetim',
            meta: {
                name: 'Değerlendirme Yönetim',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/bitis/DegerlendirmeYonetim`),
            name: 'Değerlendirme Yönetim'
        },
        {
            path: '/isyeriegitimi/durum/liste',
            meta: {
                name: 'İşyeri Eğitimi Durumları',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/bitis/DurumListe`),
            name: 'İşyeri Eğitimi Durumları'
        },

        {
            path: '/isyeriegitimi/bitis-anketi/ogrenci-listesi',
            meta: {
                name: 'Firma Bitiş Öğrenci Listesi',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/bitis/FirmaBitisOgrenciList`),
            name: 'Firma Bitiş Öğrenci Listesi'
        },
        {
            path: '/isyeriegitimi/bitis-anketi/firma/:id',
            meta: {
                name: 'Firma Bitiş Anketi',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/bitis/FirmaBitisAnketi`),
            name: 'Firma Bitiş Anketi'
        },
        {
            path: '/isyeriegitimi/bitis-anketi/firma/:id/cevaplar',
            meta: {
                name: 'Firma Bitiş Anketi Cevaplar',
                requiresAuth: true
            },
            component: () => import(`../isyeriegitimi/views/bitis/FirmaBitisAnketiCevaplar`),
            name: 'Firma Bitiş Anketi Cevaplar'
        },


    ]
};

