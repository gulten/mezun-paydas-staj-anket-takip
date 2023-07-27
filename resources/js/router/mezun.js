import store from '../store';

export default {
    path: '/mezun',
    component: () => import(`../mezun/container/Mezun`),
    redirect: '/mezun/dashboard',
    children: [
        {
            path: '/mezun/dashboard',
            meta: {
                name: 'Mezun Dashboard View',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/dashboard/Dashboard`),
            name: 'Mezun Dashboard'
        },
        {
            path: '/mezun/mezun-kisisel-bilgiler',
            meta: {
                name: 'Kişisel Mezun Bilgileri',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/mezun/Mezun`),
            name: 'Mezun Profil'
        },
        {
            path: '/mezun/mezun-isdeneyimi',
            meta: {
                name: 'Kişisel Mezun İş Deneyimleri',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/mezun/IsDeneyimi`),
            name: 'Mezun İş Deneyimi'
        },
        {
            path: '/mezun/mezun-anket',
            meta: {
                name: 'Mezun Anket',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/mezun/Anket`),
            name: 'Mezun Anket'
        },
        {
            path: '/mezun/paydas-kisisel-bilgiler',
            meta: {
                name: 'Kişisel Paydaş Bilgileri',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/paydas/Paydas`),
            name: 'Paydaş Profil'
        },
        {
            path: '/mezun/paydas-isdeneyimi',
            meta: {
                name: 'Kişisel Paydas İş Deneyimleri',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/paydas/IsDeneyimi`),
            name: 'Paydai İş Deneyimi'
        },
        {
            path: '/mezun/bolume-oneriler/ders',
            meta: {
                name: 'Bölüme Öneriler',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/bolume-oneriler/List`),
            name: 'Bölüme Öneriler'
        },
        {
            path: '/mezun/bolume-oneriler/gereklilik',
            meta: {
                name: 'Bölüme Öneriler Gereklilik',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/bolume-oneriler/Gereklilik`),
            name: 'Bölüme Öneriler Gereklilik'
        },
        {
            path: '/mezun/bolume-oneriler/kalite',
            meta: {
                name: 'Bölüme Öneriler Kalite',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/bolume-oneriler/Kalite`),
            name: 'Bölüme Öneriler Kalite'
        },
        {
            path: '/mezun/bolume-oneriler/yeniders',
            meta: {
                name: 'Bölüme Öneriler Yeni Ders',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/bolume-oneriler/YeniDersOneri`),
            name: 'Bölüme Öneriler Yeni Ders'
        },
        {
            path: '/mezun/bolume-oneriler/populerteknoloji',
            meta: {
                name: 'Bölüme Öneriler Popüler Teknoloji',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/bolume-oneriler/PopulerTeknoloji`),
            name: 'Bölüme Öneriler Popüler Teknoloji'
        },
        {
            path: '/mezun/bolume-oneriler/egitimkalite',
            meta: {
                name: 'Bölüme Öneriler Eğitim Kalite',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/bolume-oneriler/EgitimKalite`),
            name: 'Bölüme Öneriler Eğitim Kalite'
        },
        {
            path: '/mezun/bolume-oneriler/diger',
            meta: {
                name: 'Bölüme Öneriler Diğer',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/bolume-oneriler/DigerOneri`),
            name: 'Bölüme Öneriler Diğer'
        },
        {
            path: '/mezun/mezun/rapor',
            meta: {
                name: 'Mezun Raporlama',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/raporlar/Mezun`),
            name: 'Mezun Rapor'
        },
        {
            path: '/mezun/paydas/rapor',
            meta: {
                name: 'Paydaş Raporlama',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/raporlar/Paydas`),
            name: 'Paydaş Rapor'
        },
        {
            path: '/mezun/isdeneyimi/rapor',
            meta: {
                name: 'İş Deneyimi Raporlama',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/raporlar/IsDeneyimi`),
            name: 'İş Deneyimi Rapor'
        },
        {
            path: '/mezun/bolume-oneriler/rapor',
            meta: {
                name: 'Bölüme Öneriler Raporlama',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/raporlar/BolumeOneriler`),
            name: 'Bölüme Öneriler Rapor'
        },
        {
            path: '/mezun/bolume-oneriler/gereklilik/rapor',
            meta: {
                name: 'Bölüme Öneriler Gereklilik Raporlama',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/raporlar/Gereklilik`),
            name: 'Bölüme Öneriler Gereklilik Rapor'
        },
        {
            path: '/mezun/bolume-oneriler/kalite/rapor',
            meta: {
                name: 'Bölüme Öneriler Kalite Raporlama',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/raporlar/Kalite`),
            name: 'Bölüme Öneriler Kalite Rapor'
        },
        {
            path: '/mezun/bolume-oneriler/yeniders/rapor',
            meta: {
                name: 'Bölüme Öneriler Yeni Ders Raporlama',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/raporlar/YeniDersOneri`),
            name: 'Bölüme Öneriler Yeni Ders Rapor'
        },
        {
            path: '/mezun/bolume-oneriler/populerteknoloji/rapor',
            meta: {
                name: 'Bölüme Öneriler Popüler Teknoloji Raporlama',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/raporlar/PopulerTeknoloji`),
            name: 'Bölüme Öneriler Popüler Teknoloji Rapor'
        },
        {
            path: '/mezun/bolume-oneriler/egitimkalite/rapor',
            meta: {
                name: 'Bölüme Öneriler Eğitim Kalite Raporlama',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/raporlar/EgitimKalite`),
            name: 'Bölüme Öneriler Eğitim Kalite Rapor'
        },
        {
            path: '/mezun/bolume-oneriler/diger/rapor',
            meta: {
                name: 'Bölüme Öneriler Diğer Raporlama',
                requiresAuth: true
            },
            component: () => import(`../mezun/views/raporlar/DigerOneri`),
            name: 'Bölüme Öneriler Diğer Kalite Rapor'
        },
    ]
};

