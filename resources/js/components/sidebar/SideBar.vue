<template>
    <div>
        <v-app-bar
            app
            color="blue-grey darken-3"
            dark
        >
            <v-toolbar-title></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn text @click="logout">
                <span>Oturumu Kapat</span>
                <v-icon right>mdi-exit-to-app</v-icon>
            </v-btn>
        </v-app-bar>

    </div>
</template>

<script>
    export default {
        props: {
            source: String,
        },
        data: () => ({
            drawer: false,
            menus: [
                { icon: 'home', text: 'Dashboard', route: '/bbys/dashboard' },
                { icon: 'account-group', text: 'Kullanıcı Listesi', route: '/bbys/users-list' },
                { icon: 'file-document-box-multiple-outline', text: 'Bölüm Bilgileri', route: '/bbys/bolum-bilgileri' },
            ],
            SideBarList: [],
        }),
        methods: {
            getSideBar () {
                this.$http.get('/api/permissions/menu-form')
                    .then(response => {
                        this.SideBarList = response.data;
                    })
                    .catch(error => console.log(error))
            },
            logout(){
                this.$store.dispatch('logout').then(()=>{
                    this.$router.push('/');
                });
            }
        }
    };
</script>
