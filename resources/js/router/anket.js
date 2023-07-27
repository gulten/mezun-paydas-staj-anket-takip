import store from '../store';

export default {
    path: '/anket',
    component: () => import(`../anket/container/Anket`),
    redirect: '/anket/dashboard',
    children: [
        {
            path: '/anket/dashboard',
            meta: {
                name: 'Anket Dashboard View',
                requiresAuth: true
            },
            component: () => import(`../anket/views/dashboard/Dashboard`),
            name: 'Anket Dashboard'
        },
        {
            path: '/anket/mezun-anketi',
            props: {
                type: "mezun-anketi",
                id: 1
            },
            meta: {
                name: 'Mezun Anketi Görüntüle',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/View`),
            name: 'Mezun Anketi Görüntüle'
        },
        {
            path: '/anket/mezun-anketi/edit',
            props: {
                type: "mezun-anketi",
                id: 1
            },
            meta: {
                name: 'Mezun Anketi Düzenle',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/Edit`),
            name: 'Mezun Anketi Düzenle'
        },
        {
            path: '/anket/akademik-personel-anketi',
            props: {
                type: "akademik-personel-anketi",
                id: 2
            },
            meta: {
                name: 'Akademik Personel Anketi',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/View`),
            name: 'Akademik Personel Anketi'
        },
        {
            path: '/anket/akademik-personel-anketi/edit',
            props: {
                type: "akademik-personel-anketi",
                id: 2
            },
            meta: {
                name: 'Akademik Personel Anketi Düzenle',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/Edit`),
            name: 'Akademik Personel Anketi Düzenle'
        },
        {
            path: '/anket/ogrenci-memnuniyet-anketi',
            props: {
                type: "ogrenci-memnuniyet-anketi",
                id: 3
            },
            meta: {
                name: 'Öğrenci Memnuniyet Anketi',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/View`),
            name: 'Öğrenci Memnuniyet Anketi'
        },
        {
            path: '/anket/ogrenci-memnuniyet-anketi/edit',
            props: {
                type: "ogrenci-memnuniyet-anketi",
                id: 3
            },
            meta: {
                name: 'Öğrenci Memnuniyet Anketi Düzenle',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/Edit`),
            name: 'Öğrenci Memnuniyet Anketi Düzenle'
        },
        {
            path: '/anket/personel-memnuniyet-anketi',
            props: {
                type: "personel-memnuniyet-anketi",
                id: 4
            },
            meta: {
                name: 'Personel Memnuniyet Anketi',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/View`),
            name: 'Personel Memnuniyet Anketi'
        },
        {
            path: '/anket/personel-memnuniyet-anketi/edit',
            props: {
                type: "personel-memnuniyet-anketi",
                id: 4
            },
            meta: {
                name: 'Personel Memnuniyet Anketi Düzenle',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/Edit`),
            name: 'Personel Memnuniyet Anketi Düzenle'
        },
        {
            path: '/anket/yenigelen-ogrenci-anketi',
            props: {
                type: "yenigelen-ogrenci-anketi",
                id: 5
            },
            meta: {
                name: 'Yeni Gelen Öğrenci Anketi',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/View`),
            name: 'Yeni Gelen Öğrenci Anketi'
        },
        {
            path: '/anket/yenigelen-ogrenci-anketi/edit',
            props: {
                type: "yenigelen-ogrenci-anketi",
                id: 5
            },
            meta: {
                name: 'Yeni Gelen Öğrenci Anketi Düzenle',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/Edit`),
            name: 'Yeni Gelen Öğrenci Anketi Düzenle'
        },
        {
            path: '/anket/isyeriegitimi-ogrenci-anketi',
            props: {
                type: "isyeriegitimi-ogrenci-anketi",
                id: 6
            },
            meta: {
                name: 'İşyeri Eğitimi Öğrenci Anketi',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/View`),
            name: 'İşyeri Eğitimi Öğrenci Anketi'
        },
        {
            path: '/anket/isyeriegitimi-ogrenci-anketi/edit',
            props: {
                type: "isyeriegitimi-ogrenci-anketi",
                id: 6
            },
            meta: {
                name: 'İşyeri Eğitimi Öğrenci Anketi Düzenle',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/Edit`),
            name: 'İşyeri Eğitimi Öğrenci Anketi Düzenle'
        },
        {
            path: '/anket/isyeriegitimi-isyeri-anketi',
            props: {
                type: "isyeriegitimi-isyeri-anketi",
                id: 7
            },
            meta: {
                name: 'İşyeri Eğitimi İşyeri Anketi',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/View`),
            name: 'İşyeri Eğitimi İşyeri Anketi'
        },
        {
            path: '/anket/isyeriegitimi-isyeri-anketi/edit',
            props: {
                type: "isyeriegitimi-isyeri-anketi",
                id: 7
            },
            meta: {
                name: 'İşyeri Eğitimi İşyeri Anketi Düzenle',
                requiresAuth: true
            },
            component: () => import(`../anket/views/anketler/Edit`),
            name: 'İşyeri Eğitimi İşyeri Anketi Düzenle'
        },
        {
            path: '/anket/anket-raporlari',
            meta: {
                name: 'Anket Raporları',
                requiresAuth: true
            },
            component: () => import(`../anket/views/raporlar/List`),
            name: 'Anket Raporları'
        },
        {
            path: '/anket/cevap/:id',
            meta: {
                name: 'Anket Cevap',
                requiresAuth: true
            },
            component: () => import(`../anket/views/raporlar/Cevap`),
            name: 'Anket Cevap'
        },
    ]
};

