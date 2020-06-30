<template>
    <v-form @submit="createAccount" onsubmit="return false;">
        <v-container>
            <v-layout>
                <v-flex xs12 md4>
                    <v-text-field
                            v-model="name"
                            label="name"
                            required
                    ></v-text-field>
                    <v-text-field
                            v-model="twitterId"
                            label="twitterId"
                            required
                    ></v-text-field>
                    <v-text-field
                            v-model="email"
                            label="E-mail"
                            required
                    ></v-text-field>
                    <v-text-field
                            v-model="pass"
                            label="password"
                            required
                    ></v-text-field>
                    <v-text-field
                            v-model="passConf"
                            label="passConf"
                            required
                    ></v-text-field>
                    <v-btn color="primary" type="submit">アカウント作成</v-btn>
                </v-flex>
            </v-layout>
        </v-container>
    </v-form>
</template>

<script>
    import {CREATE_ACCOUNT} from "../graphql/mutation.js";
    import store from '../store/index.js';
    export default {
        data() {
            return {
                name: "",
                twitterId: "",
                email: "",
                pass: "",
                passConf: ""
            }
        },
        methods: {
            createAccount(e) {
                this.$apollo.mutate({
                    mutation: CREATE_ACCOUNT,
                    variables: {
                        name: this.name,
                        twitter_id: this.twitterId,
                        email: this.email,
                        password: this.pass,
                        password_confirmation: this.passConf
                    },
                }).then((data) => {
                    console.log(data);
                    const token = localStorage.getItem("vue_token", data.data.CreateAccount.token.access_token);
                    store.commit("logined");
                    this.$router.push("/");
                }).catch((error) => {
                    console.error(error);
                })
            }
        }
    }
</script>

<style scoped>

</style>
