<template>
    <v-row>
        <v-col
            cols="12"
        >
        <side-bar/>
            <v-row
                align="center"
                justify="center"
                class="grey lighten-5 flex-column"
            >

                <v-hover
                    v-if="modules.bbys"
                    v-slot:default="{ hover }"
                    open-delay="50"
                >
                    <v-card
                        color="blue-grey darken-3"
                        dark
                        :elevation="hover ? 16 : 2"
                        class="col-8 ma-3 pa-6"
                    >
                        <v-card-title class="headline text-no-wrap">BÖLÜM BİLGİ YÖNETİM MODÜLÜ</v-card-title>
                        <v-card-actions>
                            <v-btn text outlined @click="enter('bbys')">GİRİŞ</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-hover>

                <v-hover
                    v-if="modules.mezun"
                    v-slot:default="{ hover }"
                    open-delay="50"
                >
                    <v-card
                        color="blue-grey darken-3"
                        dark
                        :elevation="hover ? 16 : 2"
                        class="col-8 ma-3 pa-6"
                    >
                        <v-card-title class="headline">MEZUN-PAYDAŞ TAKİP MODÜLÜ</v-card-title>
                        <v-card-actions>
                            <v-btn text outlined @click="enter('mezun')">GİRİŞ</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-hover>

                <v-hover
                    v-if="modules.isyeriegitimi"
                    v-slot:default="{ hover }"
                    open-delay="50"
                >
                    <v-card
                        color="blue-grey darken-3"
                        dark
                        :elevation="hover ? 16 : 2"
                        class="col-8 ma-3 pa-6"
                    >
                        <v-card-title class="headline">İŞYERİ EĞİTİMİ MODÜLÜ</v-card-title>
                        <v-card-actions>
                            <v-btn text outlined @click="enter('isyeriegitimi')">GİRİŞ</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-hover>

                <v-hover
                    v-if="modules.anket"
                    v-slot:default="{ hover }"
                    open-delay="50"
                >
                    <v-card
                        color="blue-grey darken-3"
                        dark
                        :elevation="hover ? 16 : 2"
                        class="col-8 ma-3 pa-6"
                    >
                        <v-card-title class="headline">ANKET MODÜLÜ</v-card-title>
                        <v-card-actions>
                            <v-btn text outlined @click="enter('anket')">GİRİŞ</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-hover>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    import SideBar from '../../components/sidebar/SideBar';
    import Footer from '../../components/footer/Footer';
    export default {
        components:{
            SideBar,
            Footer
        },
        data: () => ({
            modules: {
                bbys:false,
                mezun:false,
                isyeriegitimi:false,
                anket:false,
            }
        }),
        created () {
            this.getUserPermissions();
        },
        methods: {
            getUserPermissions() {
                api.get('permissions/user')
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.permissions;
                            this.modules.bbys = this.Items.filter(item=>item.name==="bbys modülü görüntüle").length?true:false;
                            this.modules.mezun  = this.Items.filter(item=>item.name==="mezun modülü görüntüle").length?true:false;
                            this.modules.isyeriegitimi  = this.Items.filter(item=>item.name==="isyeriegitimi modülü görüntüle").length?true:false;
                            this.modules.anket  = this.Items.filter(item=>item.name==="anket modülü görüntüle").length?true:false;
                        }
                    })
            },
            enter (system) {
                    //system Local Storage
                    this.$store.dispatch('saving_system', { system })
                        .then(() => this.$router.push(system + '/dashboard'))
            },
        },
        mounted() {
        }
    }
</script>
