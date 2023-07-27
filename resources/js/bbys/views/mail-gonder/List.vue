<template>
    <div>
        <v-snackbar top :color="color" :timeout="timeout" v-model="snackbar">
            {{ snackbarMessage }}
        </v-snackbar>
        <v-dialog
            v-model="dialog"
            max-width="60%"
            >
            <v-card>
                <v-card-title class="headline text-center">GÖNDERİLECEK MAİL</v-card-title>
                <v-card-text class="mt-3 mb-3">
                    <v-col cols="12">
                        <v-autocomplete
                            v-model="draft"
                            :items="draftList"
                            label="Seçilen Mail Taslağı"
                            item-value="id"
                            item-text="baslik"
                            return-object
                            dense
                            outlined
                            @change="changeDraft"
                            :disabled="disabled"
                        >
                        </v-autocomplete>
                    </v-col>
                    <v-col cols="12" sm="12">
                        <v-text-field
                        v-model="baslik"
                        label="Mail Başlığı"
                        :rules="stringRules"
                        outlined
                        >
                        </v-text-field>
                    </v-col>
                    <v-col cols="12" sm="12">
                        <v-textarea
                        v-model="metin"
                        label="Mail Metni"
                        :rules="stringRules"
                        outlined
                        >
                        </v-textarea>
                    </v-col>
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="green darken-1"
                    text
                    @click="dialog = false"
                >
                    Kapat
                </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <div class="container">
            <v-card>
                <v-card-title class="elevation-1">
                    FİLTRELER
                </v-card-title>
                <v-card-text>
                <v-row>
                    <v-col cols="12">
                        <v-autocomplete
                            v-model="role"
                            :items="roleList"
                            label="Role Göre Kullanıcı Getir"
                            item-value="name"
                            item-text="name"
                            dense
                            outlined
                            persistent-hint
                            hint="Seçtiğiniz Role Ait Kullanıcılar Getirilecektir"
                            @change="getUsers"
                            :disabled="disabled"
                        >
                        </v-autocomplete>
                    </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
            <v-card class="mt-4">
                <v-card-title>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-account-search"
                        label="Ad veya E-Mail Filtrele"
                        hide-details
                    ></v-text-field>
                </v-card-title>
                <v-card-text>
                    <v-data-table
                        v-model="selected"
                        :headers="headers"
                        :items="Items"
                        class="elevation-1"
                        sort-by="id"
                        :items-per-page="rowsPerPage"
                        :search="search"
                        show-select
                        :loading="loading" loading-text="İşlem Yapılıyor, Lütfen Bekleyin"
                    >
                        <template v-slot:top>
                            <v-toolbar  class="elevation-1 mb-4" color="white">
                                <v-toolbar-title>KULLANICI SEÇİMİ</v-toolbar-title>
                                <v-divider
                                    class="mx-4"
                                    inset
                                    vertical
                                ></v-divider>
                                <v-spacer></v-spacer>
                                <template>
                                    <v-btn
                                        @click="sendMails"
                                        color="primary"
                                        dark
                                        class="mb-2"
                                        :disabled="disabled"
                                    >
                                        <v-icon left>mdi-email-send</v-icon>
                                        GÖNDERİME BAŞLA
                                    </v-btn>
                                    <v-btn
                                        @click="stopMails"
                                        color="primary"
                                        dark
                                        class="mb-2"
                                        :disabled="!disabled"
                                    >
                                        <v-icon left>mdi-email-send</v-icon>
                                        GÖNDERİMİ DURDUR
                                    </v-btn>
                                    <v-btn
                                        @click="[dialog = true]"
                                        color="primary"
                                        dark
                                        class="mb-2 ml-3"
                                        :disabled="disabled"
                                    >
                                        <v-icon left>mdi-email-edit</v-icon>
                                        MAİL METNİNİ DÜZENLE
                                    </v-btn>
                                </template>
                            </v-toolbar>
                        </template>
                        <template v-slot:item.action="{ item }">
                            <v-btn
                            small
                            :loading="item.loadingmail"
                            color="indigo"
                            class="ma-2 white--text"
                            fab
                            @click="sendMail(item)"
                            >
                                <v-icon dark>mdi-email-send-outline</v-icon>
                            </v-btn>
                        </template>
                        <template v-slot:no-data>
                            <v-btn color="primary" @click="getUsers">Reset</v-btn>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </div>
    </div>
</template>
<script>
    import moment from 'moment';
    import jsPDF from 'jspdf';
    import 'jspdf-autotable';
    export default {
        components:{
        },
        data: () => ({
            search: '',
            selected: [],
            rowsPerPage: 25,
            active: null,
            dialog: false,
            disabled: false,
            datacollection:{ labels:[], datasets: [] },
            show: false,
            buttonLoader: false,
            validForm: true,
            color: 'success',
            snackbar: false,
            snackbarMessage: '',
            loading: null,
            loadingmail: false,
            timeout: 2000,
            stringRules: [
            v => !!v || 'Bu alan gereklidir',
            ],
            headers: [
                { text: 'AD', value: 'name' },
                { text: 'E-MAIL', value: 'email' },
                { text: 'İŞLEM', value: 'action', sortable: false },
            ],
            Items: [],
            roleList: [],
            role: '',
            draftList: [],
            draft: '',
            baslik: '',
            metin: '',
            sendButtonLabel: 'GÖNDERİMİ BAŞLAT'
        }),

        computed: {
        },

        watch: {
            disabled (val) {
                //val ?  this.sendButtonLabel ='GÖNDERİMİ DURDUR' : this.sendButtonLabel ='GÖNDERİMİ BAŞLAT'
            },
        },

        created () {
            this.getAllRoles();
            this.getAllDrafts();
        },

        methods: {
            getAllRoles(){
                api.get('/bbys/all_roles')
                    .then(response => {
                        if (response.data.success) {
                            this.roleList = response.data.data.roller;
                        }
                    })
            },
            getAllDrafts(){
                api.get('/bbys/mail-taslaklari')
                    .then(response => {
                        if (response.data.success) {
                            this.draftList = response.data.data.mail_taslaklari;
                        }
                    })
            },
            getUsers () {
                let formData = new FormData();
                formData.append('role', this.role);
                api.post('/bbys/mail-gonder/UserList', formData)
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.kullanicilar;
                            this.selected = this.Items;
                        }
                    })
            },
            changeDraft(){
                this.baslik = this.draft.baslik;
                this.metin = this.draft.metin;
            },
            stopMails() {
                this.loading = false;
                this.disabled = false;
                this.color = 'warning';
                this.snackbar = true;
                this.snackbarMessage = 'Mail Gönderimi Tamamlanamadı!';
            },
            async sendMails() {
                if (this.selected.length<=0){
                    this.color = 'warning';
                    this.snackbar = true;
                    this.snackbarMessage = 'Seçili Kullanıcı Bulunmamaktadır';
                    return false;
                }
                if ((this.baslik.length<4) || (this.metin.length<4)){
                    this.color = 'warning';
                    this.snackbar = true;
                    this.snackbarMessage = 'Mail Başlığı veya Metni Girilmemiş';
                    return false;
                }

                this.disabled = true;
                this.loading = true;
                for (let i = 0; i < this.selected.length; i++) {

                    if (this.disabled === true) {
                        this.selected[i].loadingmail = true;
                        let id = this.selected[i].id;
                        let formData = new FormData();
                        formData.append('baslik', this.baslik);
                        formData.append('metin', this.metin);
                        formData.append('_method', 'PUT');
                        await api.post('/bbys/secret_mail/' + id, formData)
                        .then(response => {
                            if (response.data.success) {
                                this.selected[i].loadingmail = false;
                            }
                        })
                    }

                }
                this.loading = false;
                this.disabled = false;
                this.color = 'success';
                this.snackbar = true;
                this.snackbarMessage = 'Mail Gönderimi Tamamlandı';
            },
            sendMail(item) {
                if ((this.baslik.length<4) || (this.metin.length<4)){
                    this.color = 'warning';
                    this.snackbar = true;
                    this.snackbarMessage = 'Mail Başlığı veya Metni Girilmemiş';
                    return false;
                }
                let id = item.id;
                this.loading = true;
                item.loadingmail = true;
                let formData = new FormData();
                formData.append('baslik', this.baslik);
                formData.append('metin', this.metin);
                formData.append('_method', 'PUT');
                api.post('/bbys/secret_mail/' + id, formData)
                    .then(response => {
                        if (response.data.success) {
                            this.color = 'success';
                            this.snackbar = true;
                            this.snackbarMessage = 'Kullanıcıya Bilgilendirme Maili Gönderildi';
                            this.loading = false;
                            item.loadingmail = false;
                        }
                    })
                    .catch(error => {
                            this.color = 'error';
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                            this.loading = false;
                            item.loadingmail = false;
                        })

            },
        },
    }
</script>
