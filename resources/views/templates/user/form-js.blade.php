var base = require('../components/Form/Form');

Vue.component('{{ $modelJSName }}-form', {
    mixins: [base]@if($translatable->count() > 0),
    data: function() {
        return {
            form: {
                @foreach($translatable as $translatableField){{ $translatableField }}: {},
                @endforeach

            }
        }
    }
    @endif,
    props: {
        'activation': {
            type: Boolean,
            required: true
        },
    }
});