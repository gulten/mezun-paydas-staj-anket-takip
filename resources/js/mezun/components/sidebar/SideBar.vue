<template>
    <div>
        <v-navigation-drawer
            v-model="drawer"
            app
        >
            <v-list dense>
                <template v-for="(item, index) in menus">
                    <template v-if="item.sub_menu">
                        <v-list-group
                            :key="item.text"
                            :prepend-icon="`mdi-` + item.icon"
                            no-action
                        >
                            <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title v-text="item.text"></v-list-item-title>
                            </v-list-item-content>
                            </template>

                            <v-list-item
                            v-for="subItem in item.group"
                            :key="subItem.text"
                            :to="subItem.route"
                            style="text-decoration: none;"
                            >
                            <v-list-item-content>
                                <v-list-item-title v-text="subItem.text"></v-list-item-title>
                            </v-list-item-content>

                            </v-list-item>
                        </v-list-group>
                    </template>

                    <template v-else>
                        <v-list-item
                            :key="index"
                            router
                            :to="item.route"
                            style="text-decoration: none;">
                            <v-list-item-action>
                                <v-icon>mdi-{{ item.icon }}</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{ item.text }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </template>

                </template>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar
            app
            color="blue-grey darken-3"
            dark
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
            <v-toolbar-title class="font-weight-light">SİMATAKİP SİSTEMİ</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn text>
                <span>MEZUN-PAYDAŞ TAKİP MODÜLÜ</span>
                <v-icon right>mdi-exit-to-app</v-icon>
            </v-btn>

            <v-menu
            v-model="menu"
            :close-on-content-click="false"
            :nudge-width="200"
            offset-x
            >
                <template v-slot:activator="{ on }">
                    <v-btn
                    color="deep-orange darken-4"
                    dark
                    v-on="on"
                    @click="getUser"
                    >
                    İşlemler Menüsü
                    </v-btn>
                </template>

                <v-card>
                    <v-list>
                        <v-list-item>
                                <v-list-item-icon>
                                    <v-icon>mdi-account-circle</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>{{ user }}</v-list-item-title>
                                    <v-list-item-subtitle>{{ role || 'Misafir' }}</v-list-item-subtitle>
                                </v-list-item-content>

                        </v-list-item>
                    </v-list>

                    <v-divider></v-divider>

                    <v-list>
                    <v-list shaped>
                        <v-list-item-group color="primary">
                             <v-list-item router to="/select_role" style="text-decoration: none;">
                                <v-list-item-icon>
                                    <v-icon>mdi-contacts</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Rol Seçim Ekranına Dön</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                             <v-list-item router to="/select_system" style="text-decoration: none;">
                                <v-list-item-icon>
                                    <v-icon>mdi-view-module</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Modül Seçim Ekranına Dön</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon>mdi-account-arrow-right</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title @click="logout">Oturumu Kapat</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                        </v-list>
                    </v-list>

                    <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn text @click="menu = false">Kapat</v-btn>
                    </v-card-actions>
                </v-card>
            </v-menu>

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
            menus: [],
            SideBarList: [],
            menu: false,
            user: '',
            role: '',
        }),
        created: function () {
            this.getSideBar();
        },
        methods: {
            getSideBar () {
                let $url;
                if (localStorage.getItem('role'))
                    $url = '/mezun/left-menu';
                else
                    $url = '/left-menu/visitor';
                api.get($url)
                    .then(response => {
                        this.menus = response.data;
                    })
                    .catch(error => console.log(error))
            },
            getUser(){
                this.user = this.$store.state.auth.user||localStorage.getItem('user');
                this.role = this.$store.state.roles.role||localStorage.getItem('role');
            },
            logout(){
                this.$store.dispatch('logout').then(()=>{
                    this.$router.push('/');
                });
            }
        }
    };
</script>
