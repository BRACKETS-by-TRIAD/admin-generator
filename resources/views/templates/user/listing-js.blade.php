import AppListing from '../components/Listing/AppListing';

Vue.component('{{ $modelJSName }}-listing', {
    mixins: [AppListing],
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
    },
    props: {
        'activation': {
            type: Boolean,
            required: true
        },
    }
});