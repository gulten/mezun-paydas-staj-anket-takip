<template>
    <v-app id="inspire">
        <side-bar/>
        <v-main>
            <v-container fluid>
                <router-view/>
            </v-container>
        </v-main>
        <Footer/>
    </v-app>
</template>

<script>
    import SideBar from '../components/sidebar/SideBar';
    import Footer from '../components/footer/Footer';
    export default {
        components:{
            SideBar,
            Footer
        }
    };
</script>
