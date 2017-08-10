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
    }
    @endif

});