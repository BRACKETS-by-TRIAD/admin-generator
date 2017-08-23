import AppForm from '../components/Form/AppForm';

Vue.component('{{ $modelJSName }}-form', {
    mixins: [AppForm]@if($translatable->count() > 0),
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