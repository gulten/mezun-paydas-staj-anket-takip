<template>
    <v-app id="inspire">
    <header/>
        <side-bar/>
        <v-main>
            <v-container fluid>
                <router-view :key="$route.fullPath"></router-view>
            </v-container>
        </v-main>
        <footer/>
    </v-app>
</template>

<script>
    import Header from '../components/header/Header';
    import SideBar from '../components/sidebar/SideBar';
    import Footer from '../components/footer/Footer';
    export default {
        components:{
            Header,
            SideBar,
            Footer
        }
    };
</script>
