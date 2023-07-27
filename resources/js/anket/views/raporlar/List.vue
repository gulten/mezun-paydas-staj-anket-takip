<template>
    <div>
        <v-snackbar top :timeout="timeout" v-model="snackbar">
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
                >
                    <template v-slot:top>
                        <v-toolbar flat color="white">
                            <v-toolbar-title>ANKET RAPORLARI</v-toolbar-title>
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                            ></v-divider>
                            <v-spacer></v-spacer>
                            <v-col class="d-flex" cols="12" sm="6">
                                <v-select
                                :items="Anketler"
                                v-model="anket"
                                label="Anket Listesi"
                                dense
                                outlined
                                hide-details
                                item-text="ad"
                                item-value="id"
                                @change="getUsers"
                                ></v-select>
                            </v-col>
                        </v-toolbar>
                    </template>

                    <template v-slot:item.user.status="{ item }">
                        <v-icon
                            v-if="item.user.status === 'active'"
                            class="mr-2"
                            color="success"
                        >
                            mdi-check-circle
                        </v-icon>
                        <v-icon
                            v-else
                            class="mr-2"
                            color="danger"
                        >
                            mdi-checkbox-blank-circle-outline
                        </v-icon>
                    </template>
                    <template v-slot:item.action="{ item }">
                        <v-icon

                            class="mr-2"
                            @click="viewItem(item)"
                        >
                            mdi-content-paste
                        </v-icon>
                    </template>
                    <template v-slot:no-data>
                        <v-btn color="primary" @click="getUsers">Reset</v-btn>
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
            snackbar: false,
            snackbarMessage: '',
            timeout: 2000,
            headers: [
                { text: 'AD', value: 'user.name' },
                { text: 'E-MAIL', value: 'user.email' },
                { text: 'STATÜ', value: 'user.status' },
                { text: 'İŞLEM', value: 'action', sortable: false },
            ],
            Anketler: [],
            anket: 1,
            Items: [],
            editedIndex: -1,
            editedItem: {
                id: 0,
                name: '',
                email: '',
                telefon: '',
                mezuniyet_tarihi: '',
                mezuniyet_suresi: '',
                status: 'active',
                password: '',
                password_confirmation: '',
            },
            defaultItem: {
                id: 0,
                name: '',
                email: '',
                telefon: '',
                mezuniyet_tarihi: '',
                mezuniyet_suresi: '',
                status: 'active',
                password: '',
                password_confirmation: '',
            },
        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'Yeni Kayıt' : 'Kayıt Düzenle'
            },
        },

        watch: {
            dialog (val) {
                val || this.close()
            },
        },

        created () {
            this.getUsers();
            this.getRaporList();
        },

        methods: {
            getRaporList () {
                api.get('/anket/rapor/list')
                    .then(response => {
                        if (response.data.success) {
                            this.Anketler = response.data.data.anketler;
                        }
                    });
            },
            getUsers () {
                api.get('/anket/rapor/' + this.anket)
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.anketler;
                        }
                    });
            },
            viewItem(item) {
                this.$router.push('/anket/cevap/' + item.id);
            },
            close () {
                this.dialog = false;
                this.$refs.dialogform.resetValidation();
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1
                }, 300)
            },
            closeModal(){
                this.$refs.form.resetValidation();
                this.buttonLoader = false;
                this.passwordModal = false;
                this.active = 0;
            },
        },
    }
</script>
