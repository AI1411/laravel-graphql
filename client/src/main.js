import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify';
import {ApolloClient} from 'apollo-client';
import {setContext} from 'apollo-link-context';
import {createHttpLink} from 'apollo-link-http';
import {InMemoryCache} from "apollo-cache-inmemory";

import VueApollo from 'vue-apollo';

Vue.config.productionTip = false

Vue.use(VueApollo);

const httpLink = createHttpLink({
  uri: "http://127.0.0.1:8000/graphql"
});

const authLink = setContext((_, {headers}) => {
  const token = localStorage.getItem("vue_token");
  return {
    headers: {
      ...headers,
      authorization: token ? `Bearer ${token}` : ""
    }
  }
});

const apolloClient = new ApolloClient({
  link: authLink.concat(httpLink),
  cache: new InMemoryCache()
});

const apolloProvider = new VueApollo({
  defaultClient: apolloClient,
  errorHandler(error) {
    store.commit("error");
  }
})

new Vue({
  router,
  store,
  apolloProvider,
  vuetify,
  render: h => h(App)
}).$mount('#app')
