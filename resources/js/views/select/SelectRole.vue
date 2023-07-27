<template>
    <v-row>
        <v-col
            cols="12"
        >
        <side-bar/>
            <v-row
                align="center"
                justify="center"
            >
                <v-card
                    class="col-8 ma-3 pa-6"
                    flat
                    outlined
                >
                    <v-card-title class="headline">YETKİ SEÇİMİ</v-card-title>
                    <v-form ref="form"
                            v-model="valid">
                    <v-card-actions>
                        <v-chip
                            v-for="item in Items"
                            :key="item"
                            class="ma-2"
                            color="blue-grey"
                            text-color="white"
                            label
                            @click="submit(item)"
                            >
                            <v-icon left>mdi-account-circle-outline</v-icon>
                            {{ item }}
                            </v-chip>

                            <v-chip
                            class="ma-2"
                            color="blue-grey"
                            text-color="white"
                            label
                            @click="submit('')"
                            >
                            <v-icon left>mdi-account-circle-outline</v-icon>
                            Misafir
                            </v-chip>

                    </v-card-actions>
                    </v-form>
                </v-card>
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
            Items: [],
            valid: false,
        }),
        created () {
            this.getUserRoles();
        },

        methods: {
            getUserRoles() {
                api.get('roles/user')
                    .then(response => {
                        if (response.data.success) {
                            this.Items = response.data.data.roles;
                        }
                    })
            },
            submit (item) {
                if(this.valid){
                    //role Local Storage

                    let role = item;
                    api.defaults.headers.common['Role'] = role;
                    this.$store.dispatch('saving_role', { role })
                        .then(() => this.$router.push('./select_system'))
                        .catch(err => {
                                this.snackbar= true
                            }
                        )
                }

            },
        },
        mounted() {

        }
    }
</script>
