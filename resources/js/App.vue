<template>
        <router-view/>
</template>

<script>
    // checks to see if auth jwt token is valid or has expired, if it gets back 401 error log out
    export default {
        created: function () {



           /* this.$http.interceptors.response.use( (response) => {
                return response;
            }, (error) => {
                if (401 === error.response.status) {
                    if (this.$store.getters.authorized) {
                        this.$store.dispatch('refreshtoken')
                    }else {
                        return Promise.reject(error);
                    }

                } else {
                    return Promise.reject(error);
                }
            });*/
        }
    };
</script>
