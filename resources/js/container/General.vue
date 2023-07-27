<template>
    <v-app id="inspire">
        <header/>
        <!-- Velocity animation example -->
        <notifications group="loggedIn"
                   position="top left"
                   animation-type="velocity"
                   :speed="500" />
        <v-main>
            <v-container fluid>
                <router-view/>
            </v-container>
        </v-main>
        <footer/>
    </v-app>
</template>

<script>
    import Header from '../components/header/Header';
    import Footer from '../components/footer/Footer';
    export default {
        components:{
            Header,
            Footer
        }
    };
</script>
