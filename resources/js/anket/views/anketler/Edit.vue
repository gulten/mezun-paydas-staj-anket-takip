<template>
    <div>
        <div class="container">
            <v-snackbar
                        v-model="snackbar"
                        :timeout="2000"
                        absolute
                        top
                        center
                    >
                        {{ snackbarMessage }}
            </v-snackbar>
            <v-card
                class="overflow-hidden"
                color="lighten-1"
            >
                <v-toolbar
                    flat
                >
                    <v-icon>mdi-account-box-multiple-outline</v-icon>
                    <v-toolbar-title class="title"> {{ anket.ad }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn
                    @click="save"
                        color="darken-3"
                        fab
                        small
                        class="mr-2"
                    >
                        <v-icon>mdi-content-save</v-icon>
                    </v-btn>
                    <v-btn
                        :to="`/anket/${type}`"
                        color="darken-3"
                        fab
                        small
                    >
                        <v-icon>mdi-file-table-box-multiple</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text>
                    <div class="row">
                        <div class="col-3">
                            <h3>Element Seç</h3>
                            <draggable
                                class="dragArea list-group"
                                :list="basicComponentList"
                                :group="{ name: 'anket', pull: 'clone', put: false }"
                                :clone="cloneElement"
                                @change="log"
                            >
                                <div class="list-group-item" v-for="(element, index) in basicComponentList" :key="index">
                                    <v-icon v-text="`mdi-` + element.icon"></v-icon>
                                    {{ element.name }}
                                </div>
                            </draggable>
                        </div>

                        <div class="col-8">
                            <h3>Anket</h3>
                            <draggable
                                class="dragArea list-group"
                                :list="editElement"
                                group="anket"
                                @change="log"
                            >
                                <div class="list-group-item" v-for="(element, index) in editElement" :key="index">
                                    <div class="text-right">
                                        <div class="my-2">
                                            <v-btn @click="deleteField(element)" color="secondary" fab x-small dark>
                                                <v-icon>mdi-delete-forever</v-icon>
                                            </v-btn>
                                        </div>
                                    </div>
                                    {{ element.soru_tipi }}
                                    <v-text-field
                                        v-model="element.soru"
                                        label="Soru"
                                        outlined
                                    ></v-text-field>
                                    <template v-if="element.soru_tipi==='VRadio'||element.soru_tipi==='VSwitch'||element.soru_tipi==='VSelect'">
                                        <div class="row">
                                                <template v-for="(detail, indexDetay) in element.detay">
                                                    <div class="col-4">
                                                        <v-select
                                                        v-if="element.soru_tipi==='VRadio'"
                                                        :items="elementTypeList"
                                                        v-model="detail.tip"
                                                        label="Element Tipi Seç"
                                                        :key="indexDetay + '_type'"
                                                        outlined
                                                        ></v-select>
                                                    </div>
                                                    <div class="col-4">
                                                        <v-text-field
                                                            v-model="detail.secenek"
                                                            label="Soru"
                                                            :key="indexDetay + '_detail'"
                                                            outlined
                                                        ></v-text-field>
                                                    </div>
                                                    <div class="col-4">
                                                        <v-btn @click="removeDetailField(indexDetay, element.detay)" color="secondary" fab x-small dark>
                                                            <v-icon>mdi-minus</v-icon>
                                                        </v-btn>
                                                    </div>
                                                </template>
                                                 <v-btn class="mx-2" fab dark small color="primary" @click="addDetailField(element.detay,element.soru_tipi)">
                                                    <v-icon dark>mdi-plus</v-icon>
                                                </v-btn>
                                        </div>
                                    </template>
                                    <div class="text-left">
                                        <div class="my-2">
                                            <v-switch
                                            v-model="element.required"
                                            :true-value=1
                                            :false-value=0
                                            label="Bu Alanı Zorunlu Yap"
                                            ></v-switch>
                                        </div>
                                    </div>
                                </div>
                            </draggable>
                        </div>
                    </div>
                    <!--<pre>{{ editElement }}</pre>-->
                </v-card-text>
            </v-card>
        </div>
    </div>
</template>

<script>
    import draggable from "vuedraggable";
    export default {
        components:{
            draggable
        },
        props: ['type', 'id'],
        data: () => ({
            anket: [],
            anketSorusu: [],
            editElement: [],
            elementTypeList: [
                "VRadio",
                "VTextField",
            ],
            snackbarMessage: '',
            snackbar: false
        }),
         computed: {
            basicComponentList: {
                get() {
                    return this.$store.state.anket.basicComponentList
                },
                set(value) {
                }
            },
        },
        mounted(){
            this.getData();
        },
        methods: {
            getData () {
                api.get('anket/sorular/' + this.id)
                    .then(response => {
                        if(response.data.success) {
                            this.anket = response.data.data;
                            this.editElement = response.data.data.anket_sorular;
                        }
                    })
                    .catch(error => console.log(error))
            },
            save () {
                this.anket.anket_sorular = this.editElement;
                api.post('anket/sorular', this.anket)
                    .then(response => {
                        if(response.data.success) {
                            this.snackbar = true;
                            this.snackbarMessage = 'İşlem Başarılı';
                            this.getData();
                        }
                    })
                    .catch(error => console.log(error))
            },
            deleteField (element) {
                if (element.id) {
                    api.delete('anket/sorular/' + element.id)
                        .then(response => {
                            if(response.data.success) {
                                this.snackbar = true;
                                this.snackbarMessage = 'İşlem Başarılı';
                            }
                        })
                        .catch(error => console.log(error))
                }
                const index = this.editElement.indexOf(element)
                confirm('Bu sorunun silinmesini onaylıyor musunuz?') && this.editElement.splice(index, 1);

            },
            log: function(evt) {
                window.console.log(evt);
            },
            cloneElement({ tag }) {
                if ((tag==='VRadio')||(tag==='VSelect')||(tag==='VSwitch'))
                    return {
                        id: null,
                        soru_tipi: tag,
                        soru: "",
                        detay: [
                            {
                                tip: tag,
                                secenek: ""
                            }
                        ],
                        required: 0
                    };
                    else
                    return {
                        id: null,
                        soru_tipi: tag,
                        soru: "",
                        detay: null,
                        required: 0
                    };

            },
            addDetailField(list, type){
                return list.push({
                    tip: type,
                    secenek: "",
                });
            },
            removeDetailField(indexDetay, element){
                if (element.length>1){
                    element.splice(indexDetay, 1);
                    return element;
                }
            }

        },
    }
</script>
