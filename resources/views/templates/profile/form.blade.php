                        @foreach($columns as $col)@if($col['name'] == 'password')<div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-md-3 col-form-label text-md-right">{{ ucfirst($col['name']) }}</label>
                            <div class="col-md-9 col-xl-8">
                                <input type="password" v-model="form.{{ $col['name'] }}" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="form-control" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="{{ ucfirst($col['name']) }}">
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>

                        <div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}_confirmation'), 'has-success': this.fields.{{ $col['name'] }}_confirmation && this.fields.{{ $col['name'] }}_confirmation.valid }">
                            <label for="{{ $col['name'] }}_confirmation" class="col-md-3 col-form-label text-md-right">{{ ucfirst($col['name']) }} Confirmation</label>
                            <div class="col-md-9 col-xl-8">
                                <input type="password" v-model="form.{{ $col['name'] }}_confirmation" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="form-control" :class="{'form-control-danger': errors.has('{{ $col['name'] }}_confirmation'), 'form-control-success': this.fields.{{ $col['name'] }}_confirmation && this.fields.{{ $col['name'] }}_confirmation.valid}" id="{{ $col['name'] }}_confirmation" name="{{ $col['name'] }}_confirmation" placeholder="{{ ucfirst($col['name']) }}">
                                <div v-if="errors.has('{{ $col['name'] }}_confirmation')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>
                        @elseif($col['name'] == 'language')<div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-md-3 col-form-label text-md-right">{{ ucfirst($col['name']) }}</label>
                            <div class="col-md-9 col-xl-8">
                                <select v-model="form.{{ $col['name'] }}" v-validate="'required'" class="form-control" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}">
                                    @@foreach  ($locales as $locale)
                                        @php echo '<option value="@{{ $locale }}"@{{ $locale == old(\''.$col['name'].'\', \'\') ? \' selected="selected"\' : null }}>@{{ $locale }}</option>'; @endphp

                                        @@endforeach
                                </select>
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>@@{{ errors.first('@php echo $col['name']; @endphp') }}</div>
                            </div>
                        </div>
                        @elseif($col['type'] == 'date')<div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-md-3 col-form-label text-md-right">{{ ucfirst($col['name']) }}</label>
                            <div class="col-sm-6">
                                <datetime v-model="form.{{ $col['name'] }}" :config="datePickerConfig" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="flatpickr" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="Select a date"></datetime>
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>
                        @elseif($col['type'] == 'time')<div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-md-3 col-form-label text-md-right">{{ ucfirst($col['name']) }}</label>
                            <div class="col-sm-6">
                                <datetime v-model="form.{{ $col['name'] }}" :config="timePickerConfig" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="flatpickr" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="Select a time"></datetime>
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>
                        @elseif($col['type'] == 'datetime')<div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-md-3 col-form-label text-md-right">{{ ucfirst($col['name']) }}</label>
                            <div class="col-sm-6">
                                <datetime v-model="form.{{ $col['name'] }}" :config="datetimePickerConfig" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="flatpickr" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="Select a datetime"></datetime>
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>
                        @elseif($col['type'] == 'text')<div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-md-3 col-form-label text-md-right">{{ ucfirst($col['name']) }}</label>
                            <div class="col-md-9 col-xl-8">
                                <textarea v-model="form.{{ $col['name'] }}" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="form-control" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }" rows="3" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="Lorem ipsum dolor itum.."></textarea>
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>
                        @elseif($col['type'] == 'boolean')<div class="form-check row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
                            <div class="col-md-9 col-xl-8 offset-md-3">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" v-model="form.{{ $col['name'] }}" v-validate="'{{ implode('|', $col['frontendRules']) }}'" data-vv-name="{{ $col['name'] }}"  name="{{ $col['name'] }}_fake_element">
                                    <input type="hidden" name="{{ $col['name'] }}" :value="form.{{ $col['name'] }}">
                                    {{ ucfirst($col['name']) }}?
                                    <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                                </label>
                            </div>
                        </div>
                        @else<div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-md-3 col-form-label text-md-right">{{ ucfirst($col['name']) }}</label>
                            <div class="col-md-9 col-xl-8">
                                <input type="text" v-model="form.{{ $col['name'] }}" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="form-control" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': this.fields.{{ $col['name'] }} && this.fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="{{ ucfirst($col['name']) }}">
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>
                        @endif

                        @endforeach
