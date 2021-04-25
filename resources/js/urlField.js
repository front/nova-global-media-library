import VueClipboard from 'vue-clipboard2';

Nova.booting((Vue, router, store) => {
  Vue.use(VueClipboard);

  Vue.component('index-url-field', require('./components/urlField/IndexField').default);
});
