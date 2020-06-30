<template>
    <v-container>
        <v-layout row wrap>
            <v-flex xs12>
                <v-subheader>
                    Tweet
                </v-subheader>
            </v-flex>
            <v-flex mb-2>
                <v-sheet class="pa-4" elevation="6">
                    <v-form @submit="postTweet" onsubmit="return false;">
                        <v-text-field
                                v-model="tweet"
                                label="tweet"
                                required
                        ></v-text-field>
                        <v-btn type="submit" color="primary">ツイート</v-btn>
                    </v-form>
                </v-sheet>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import {CREATE_TWEET} from "../graphql/mutation";
    export default {
        data() {
            return {
                tweet: ""
            }
        },
        methods: {
            postTweet(e) {
                this.$apollo.mutate({
                    mutation: CREATE_TWEET,
                    variables: {
                        tweet: this.tweet
                    }
                })
            }
        }
    }
</script>
