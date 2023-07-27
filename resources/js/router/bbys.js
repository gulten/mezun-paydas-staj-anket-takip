import store from '../store';

export default {
    path: '/bbys',
    component: () => import(`../bbys/container/Bbys`),
    redirect: '/bbys/dashboard',
    children: [
        {
            path: '/bbys/dashboard',
            meta: {
                name: 'BBYS Dashboard View',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/dashboard/Dashboard`),
            name: 'Bbys Dashboard'
        },
        {
            path: '/bbys/bolum-bilgileri',
            meta: {
                name: 'Bölüm Bilgileri',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/bolum/BolumBilgileri`),
            name: 'BolumBilgileri'
        },
        {
            path: '/bbys/ogretim-elemanlari/list',
            meta: {
                name: 'Öğretim Elemanları',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/ogretim-elemanlari/List`),
            name: 'ÖgretimElemanlari'
        },
        {
            path: '/bbys/bolum-baskani-bilgileri',
            meta: {
                name: 'Bölüm Başkanı Bilgileri',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/bolum-baskani/BolumBaskani`),
            name: 'Bölüm Başkanı'
        },
        {
            path: '/bbys/bolum-baskani-yardimcilari/list',
            meta: {
                name: 'Bölüm Başkanı Yardımcıları Bilgileri',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/bolum-baskani-yardimcilari/List`),
            name: 'Bölüm Başkanı Yardımcıları Bilgileri'
        },
        /*{
            path: '/bbys/komisyonlar/list',
            meta: {
                name: 'Komisyon Bilgileri',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/komisyonlar/List`),
            name: 'Komisyon Bilgileri'
        },*/
        {
            path: '/bbys/ogrenciler/list',
            meta: {
                name: 'Öğrenci Bilgileri',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/ogrenciler/List`),
            name: 'Öğrenci Bilgileri'
        },
        {
            path: '/bbys/mezunlar/list',
            meta: {
                name: 'Mezun Bilgileri',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/mezunlar/List`),
            name: 'Mezun Bilgileri'
        },
        {
            path: '/bbys/paydaslar/list',
            meta: {
                name: 'Paydaş Bilgileri',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/paydaslar/List`),
            name: 'Paydaş Bilgileri'
        },
        {
            path: '/bbys/firma/list',
            meta: {
                name: 'Firma Bilgileri',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/firma/List`),
            name: 'Firma Bilgileri'
        },
        {
            path: '/bbys/firma_yetkilisi/list',
            meta: {
                name: 'Firma Yetkilisi Bilgileri',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/firma_yetkilisi/List`),
            name: 'Firma Yetkilisi Bilgileri'
        },
        {
            path: '/bbys/bolum-dersleri/list',
            meta: {
                name: 'Bölüm Dersleri',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/bolum-dersleri/List`),
            name: 'Bölüm Dersleri'
        },
        {
            path: '/bbys/mail-taslaklari/list',
            meta: {
                name: 'Mail Taslakları',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/mail-taslaklari/List`),
            name: 'Mail Taslakları'
        },
        {
            path: '/bbys/mail-gonder/list',
            meta: {
                name: 'Mail Gönder',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/mail-gonder/List`),
            name: 'Mail Gönder'
        },
        {
            path: '/bbys/roller/list',
            meta: {
                name: 'Rolleri Yönet',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/roller/list`),
            name: 'Rolleri Yönet'
        },
        {
            path: '/bbys/izinler/list',
            meta: {
                name: 'İzinleri Yönet',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/izinler/List`),
            name: 'İzinleri Yönet'
        },
        {
            path: '/bbys/kullanicilar/list',
            meta: {
                name: 'Kullanıcıları Yönet',
                requiresAuth: true
            },
            component: () => import(`../bbys/views/kullanicilar/List`),
            name: 'Kullanıcıları Yönet'
        }
    ]
};

