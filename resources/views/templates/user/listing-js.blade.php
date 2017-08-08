var base = require('../components/Listing/Listing');

Vue.component('{{ $modelJSName }}-listing', {
    mixins: [base],
    methods: {
        resendActivation(url) {
            axios.get(url).then(
                response => {
                    if(response.data.notify) {
                        this.$notify({ type: response.data.notify.type, title: response.data.notify.title, text: response.data.notify.message});
                    } else if (response.data.redirect) {
                        window.location.replace(response.data.redirect);
                    }
                }, error => {
                    this.$notify({ type: 'error', title: 'Error!', text: 'An error has occured.'});
                }
            );
        }
    }
});