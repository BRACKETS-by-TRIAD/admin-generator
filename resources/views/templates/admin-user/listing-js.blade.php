import AppListing from '../app-components/Listing/AppListing';

Vue.component('{{ $modelJSName }}-listing', {
    mixins: [AppListing],
    methods: {
        resendActivation(url) {
            axios.get(url).then(
                response => {
                    if(response.data.message) {
                        this.$notify({ type: 'success', title: 'Success', text: response.data.message});
                    } else if (response.data.redirect) {
                        window.location.replace(response.data.redirect);
                    }
                }, error => {
                    this.$notify({ type: 'error', title: 'Error!', text: 'An error has occured.'});
                }
            ).catch(errors => {
                    if(errors.data.message) {
                        this.$notify({ type: 'error', title: 'Error!', text: errors.data.message})
                    }
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