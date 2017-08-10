var base = require('../components/Form/Form');

Vue.component('{{ $modelJSName }}-form', {
    mixins: [base]@if($translatable),
    data: function() {
        return {
            form: {
                @foreach($translatable as $translatableField){{ $translatableField }}: {},
                @endforeach

            }
        }
    }@endif,
    methods: {
        onSuccess(data) {
            if(data.notify) {
                this.$notify({ type: data.notify.type, title: data.notify.title, text: data.notify.message});
            } else if (data.redirect) {
                window.location.replace(data.redirect);
            }
        }
    }
});