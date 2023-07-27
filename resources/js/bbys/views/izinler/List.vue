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
                        label="Search"
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
                    <template v-slot:item.permissions="{ item }">
                        <template v-for="permission in item.permissions">
                            <v-chip dark class="ma-2">{{ permission }}</v-chip>
                        </template>
                    </template>
                    <template v-slot:top>
                        <v-toolbar flat color="white">
                            <v-toolbar-title>İZİNLERİ YÖNET</v-toolbar-title>
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                            ></v-divider>
                            <v-spacer></v-spacer>
                            <v-dialog v-model="dialog" max-width="90%">
                                <!--
                                <template v-slot:activator="{ on }">
                                    <v-btn color="primary" dark class="mb-2" v-on="on">New Item</v-btn>
                                </template>-->
                                <v-card>
                                    <v-card-title>
                                        <span class="headline">{{ formTitle }}</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container>
                                            <v-row>
                                                <v-col cols="12">
                                                    <v-text-field
                                                        v-model="editedItem.name"
                                                        label="Rol"
                                                        disabled
                                                    >
                                                    </v-text-field>
                                                </v-col>
                                                <v-col cols="12">
                                                    <v-autocomplete
                                                        v-model="editedItem.permissions"
                                                        :items="allPermissions"
                                                        outlined
                                                        dense
                                                        chips
                                                        small-chips
                                                        label="İzinler"
                                                        multiple
                                                        return-object
                                                    ></v-autocomplete>
                                                </v-col>
                                            </v-row>
                                        </v-container>
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
            dialog: false,
            show: false,
            snackbar: false,
            snackbarMessage: '',
            timeout: 2000,
            headers: [
                { text: 'ROL',
                    value: 'name',
                    align: 'left',
                    sortable: false },
                { text: 'İZİNLER', value: 'permissions', sortable: false},
                { text: 'İŞLEM', value: 'action', sortable: false },
            ],
            Items: [],
            editedIndex: -1,
            allPermissions: [],
            editedItem: {
                id: 0,
                role: '',
                permissions: {
                    name: '',
                }
            },
            defaultItem: {
                id: 0,
                role: '',
                permissions: {
                    name: '',
                }
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
            this.getAllPermissions();
            this.getUsers();
        },

        methods: {
            getAllPermissions(){
                api.get('/bbys/all_permissions')
                    .then(response => {
                        if (response.data.success) {
                            this.allPermissions = response.data.data.izinler;
                        }
                    })
            },
            getUsers () {
                api.get('/bbys/izinler')
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.roller;
                        }
                    })
            },
            editItem (item) {
                this.editedIndex = this.Items.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },

            deleteItem (item) {
                const index = this.Items.indexOf(item);
                confirm('Are you sure you want to delete this item?') && this.Items.splice(index, 1)
            },

            close () {
                this.dialog = false;
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1
                }, 300)
            },

            save () {
                let item = this.editedItem;
                if (this.editedIndex > -1) {
                    let index = this.editedIndex;
                    api.put('/bbys/izinler/' + this.editedItem.id, JSON.stringify(this.editedItem))
                        .then(response => {
                            if(response.data.success) {
                                Object.assign(this.Items[index], item);
                                this.snackbar = true;
                                this.snackbarMessage = 'İşlem Başarılı';
                            }
                        })
                        .catch(error => {
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                        })

                } else {
                    api.post('/bbys/izinler', JSON.stringify(this.editedItem))
                        .then(response => {
                            if(response.data.success) {
                                this.Items.push(item);
                                this.snackbar = true;
                                this.snackbarMessage = 'İşlem Başarılı';
                            }
                        })
                        .catch(error => {
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz';
                        })

                }
                this.close()
            },
        },
    }
</script>
