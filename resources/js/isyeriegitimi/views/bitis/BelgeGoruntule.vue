<template>
    <div>
        <v-snackbar top :color="color" :timeout="timeout" v-model="snackbar">
            {{ snackbarMessage }}
        </v-snackbar>
        <div class="container">
            <v-card>
                <v-card-title>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-account-search"
                        label="Arama"
                        single-line
                        hide-details>
                    </v-text-field>
                </v-card-title>
                <v-data-table
                    :headers="headers"
                    :items="Items"
                    class="elevation-1"
                    sort-by="id"
                    :search="search"
                    :items-per-page="rowsPerPage"
                    :loading="loading" loading-text="İşlem Yapılıyor, Lütfen Bekleyin"
                >
                    <template v-slot:top>
                        <v-toolbar flat color="white">
                            <v-toolbar-title>BİTİŞ BELGELERİNİ GÖRÜNTÜLE</v-toolbar-title>
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                            ></v-divider>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                    </template>
                    <template v-slot:item.belge_yolu="{ item }">
                         <v-btn
                            v-if="item.belge_yolu!=='/upload/'"
                            icon
                            @click="fileDownload(item.belge_yolu)"
                        >
                        <v-icon dark>mdi-file-download</v-icon>
                        </v-btn>
                    </template>

                    <template v-slot:no-data>
                        <v-btn color="primary" @click="getDocuments">Reset</v-btn>
                    </template>
                </v-data-table>
            </v-card>
        </div>
    </div>
</template>
<script>
    export default {
        components:{
        },
        data: () => ({
            search: '',
            rowsPerPage: 25,
            menu: false,
            active: null,
            dialog: false,
            show: false,
            selectUser: '',
            newPassword: '',
            passwordConfirmation: '',
            buttonLoader: false,
            validForm: true,
            dialogValid: true,
            passwordModal: false,
            loading: null,
            loadingmail: false,
            color: 'success',
            snackbar: false,
            snackbarMessage: '',
            timeout: 2000,
            headers: [
                { text: 'BELGE ADI', value: 'belge_adi' },
                { text: 'BELGE GÖRÜNTÜLE', value: 'belge_yolu' },
                { text: 'BELGE AÇIKLAMASI', value: 'belge_aciklama' },
            ],
            Items: [],
            file: ''
        }),

        computed: {

        },

        watch: {
        },

        created () {
            this.getDocuments();
        },

        methods: {
            getDocuments () {
                api.get('/isyeriegitimi/bitis-belgeleri/goruntule')
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.belgeler;
                        }
                    });
            },

            fileDownload(url){
                window.open(url, '_blank');
            }

        },
    }
</script>
