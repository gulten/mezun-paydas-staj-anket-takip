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
                            <v-toolbar-title>MEZUN İŞ DENEYİMLERİ</v-toolbar-title>
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                            ></v-divider>
                            <v-spacer></v-spacer>
                            <v-dialog v-model="dialog" max-width="90%">
                                <template v-slot:activator="{ on }">
                                    <v-btn color="primary" dark class="mb-2" v-on="on">Yeni Kayıt</v-btn>
                                </template>
                                <v-card>
                                    <v-card-title>
                                        <span class="headline">{{ formTitle }}</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-form v-model="dialogValid" ref="dialogform">
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-text-field
                                                            v-model="editedItem.kurum"
                                                            label="Kurum"
                                                            required
                                                            :rules="stringRules"
                                                            >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-text-field
                                                            v-model="editedItem.birim"
                                                            label="Birim"
                                                            required
                                                            :rules="stringRules"
                                                            >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-text-field
                                                            v-model="editedItem.gorev"
                                                            label="Görev"
                                                            required
                                                            :rules="stringRules"
                                                            >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-text-field
                                                            v-model="editedItem.maas"
                                                            label="Maaş"
                                                            required
                                                            :rules="stringRules"
                                                            >
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" sm="6" md="4">
                                                    <v-menu
                                                        ref="menu"
                                                        v-model="menu"
                                                        :close-on-content-click="false"
                                                        :return-value.sync="editedItem.baslama_tarihi"
                                                        transition="scale-transition"
                                                        offset-y
                                                        max-width="290px"
                                                        min-width="290px"
                                                    >
                                                        <template v-slot:activator="{ on }">
                                                            <v-text-field
                                                                v-model="editedItem.baslama_tarihi"
                                                                label="Başlangıç Tarihi"
                                                                prepend-icon="mdi-calendar-blank-outline"
                                                                readonly
                                                                v-on="on"
                                                            ></v-text-field>
                                                        </template>
                                                        <v-date-picker
                                                            v-model="editedItem.baslama_tarihi"
                                                            no-title
                                                            scrollable
                                                            locale="tr"
                                                        >
                                                            <v-spacer></v-spacer>
                                                            <v-btn text color="primary" @click="menu = false">İptal</v-btn>
                                                            <v-btn text color="primary" @click="$refs.menu.save(editedItem.baslama_tarihi)">Seç</v-btn>
                                                        </v-date-picker>
                                                    </v-menu>
                                                </v-col>
                                                <v-col cols="12" sm="6" md="4">
                                                    <v-menu
                                                        ref="menu2"
                                                        v-model="menu2"
                                                        :close-on-content-click="false"
                                                        :return-value.sync="editedItem.ayrilma_tarihi"
                                                        transition="scale-transition"
                                                        offset-y
                                                        max-width="290px"
                                                        min-width="290px"
                                                    >
                                                        <template v-slot:activator="{ on }">
                                                            <v-text-field
                                                                v-model="editedItem.ayrilma_tarihi"
                                                                label="Ayrılma Tarihi"
                                                                prepend-icon="mdi-calendar-blank-outline"
                                                                readonly
                                                                v-on="on"
                                                            ></v-text-field>
                                                        </template>
                                                        <v-date-picker
                                                            v-model="editedItem.ayrilma_tarihi"
                                                            no-title
                                                            scrollable
                                                            locale="tr"
                                                        >
                                                            <v-spacer></v-spacer>
                                                            <v-btn text color="primary" @click="menu2 = false">İptal</v-btn>
                                                            <v-btn text color="primary" @click="$refs.menu2.save(editedItem.ayrilma_tarihi)">Seç</v-btn>
                                                        </v-date-picker>
                                                    </v-menu>
                                                </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-form>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                                        <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-toolbar>
                    </template>
                    <template v-slot:item.action="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="editItem(item)"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="deleteItem(item)"
                        >
                            mdi-delete
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
            menu2: false,
            active: null,
            dialog: false,
            show: false,
            selectUser: '',
            buttonLoader: false,
            validForm: true,
            dialogValid: true,
            snackbar: false,
            snackbarMessage: '',
            timeout: 2000,
            stringRules: [
            v => !!v || 'Bu alan gereklidir',
            ],
            headers: [
                { text: 'BAŞLAMA TARİHİ', value: 'baslama_tarihi' },
                { text: 'AYRILMA TARİHİ', value: 'ayrilma_tarihi' },
                { text: 'KURUM', value: 'kurum' },
                { text: 'BİRİM', value: 'birim' },
                { text: 'GÖREV', value: 'gorev' },
                { text: 'MAAŞ', value: 'maas' },
                { text: 'İŞLEM', value: 'action', sortable: false },
            ],
            Items: [],
            editedIndex: -1,
            editedItem: {
                id: 0,
                baslama_tarihi: '',
                ayrilma_tarihi: '',
                kurum: '',
                birim: '',
                gorev: '',
                maas: '',
                created_id: 0
            },
            defaultItem: {
                id: 0,
                baslama_tarihi: '',
                ayrilma_tarihi: '',
                kurum: '',
                birim: '',
                gorev: '',
                maas: '',
                created_id: 0
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
        },

        methods: {
            getUsers () {
                api.get('mezun/mezun-isdeneyimi')
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data[0].isdeneyimi;
                        }
                    })
                    .catch(error => console.log(error))
            },
            editItem (item) {
                this.editedIndex = this.Items.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },

            deleteItem (item) {
                if (confirm('Bu kaydı silmek istediğinize emin misiniz?')){
                    this.loader = true;
                    let index = this.Items.indexOf(item);

                    api.delete('/mezun/mezun-isdeneyimi/' + item.id).then(response => {
                        if (response.data.success) {
                            setTimeout(() => {
                                this.loader = false;
                                this.Items.splice(index, 1);
                                this.snackbar = true;
                                this.snackbarMessage = "Kayıt Başarıyla Silindi";
                            }, 1000);
                        }
                    })
                }
            },

            close () {
                this.dialog = false;
                this.$refs.dialogform.resetValidation();
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1
                }, 300)
            },

            save () {
                if (!this.$refs.dialogform.validate()) {
                    this.snackbar = true;
                    this.snackbarMessage = "İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz";
                    return false;

                }
                if (this.editedIndex > -1) {
                    let item = this.editedItem;
                    let index = this.editedIndex;
                    api.put('mezun/mezun-isdeneyimi/' + this.editedItem.id, JSON.stringify(this.editedItem))
                        .then(response => {
                            if(response.data.success) {
                                Object.assign(this.Items[index], item);
                                this.snackbar = true;
                                this.snackbarMessage = 'İşlem Başarılı';
                                this.$refs.dialogform.resetValidation();
                                this.close()
                            }
                        })
                        .catch(error => {
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                        })

                } else {
                    let item = this.editedItem;
                    api.post('mezun/mezun-isdeneyimi', JSON.stringify(this.editedItem))
                        .then(response => {
                            if(response.data.success) {
                                item = response.data.data;
                                this.Items.push(item);
                                this.snackbar = true;
                                this.snackbarMessage = 'İşlem Başarılı';
                                this.$refs.dialogform.resetValidation();
                                this.close();
                            }
                        })
                        .catch(error => {
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                        })
                }
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
