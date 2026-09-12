@php
    if (isset($form) && $form->act == 'other_bank') {
        $formData = $form->form_data;
        $wrapperClass = 'form-group';
        $formControlClass = 'form--control form--control--sm';
        $selectFieldClass = 'form-select';
    } else {
        $wrapperClass = 'col-12';
        $formControlClass = 'form--control';
        $selectFieldClass = 'wide';
    }
@endphp

@foreach ($formData as $data)
    <div class="{{ $wrapperClass }}">
        <label @class(['form--label', 'required' => $data->is_required == 'required'])>{{ __($data->name) }}</label>

        @if ($data->type == 'text')
            <input type="text" class="{{ $formControlClass }}" name="{{ $data->label }}" value="{{ old($data->label) }}" @required($data->is_required == 'required')>
        @elseif ($data->type == 'textarea')
            <textarea class="{{ $formControlClass }}" name="{{ $data->label }}" rows="10" @required($data->is_required == 'required')>{{ old($data->label) }}</textarea>
        @elseif ($data->type == 'select')
            <select class="{{ $formControlClass . ' ' . $selectFieldClass }}" name="{{ $data->label }}" @required($data->is_required == 'required')>
                <option selected disabled>@lang('Select One')</option>

                @foreach ($data->options as $item)
                    <option value="{{ $item }}" @selected($item == old($data->label))>
                        {{ __($item) }}
                    </option>
                @endforeach
            </select>
        @elseif ($data->type == 'checkbox')
            <div class="d-flex flex-wrap gap-3">
                @foreach ($data->options as $option)
                    <div class="form--check">
                        <input type="checkbox" class="form-check-input" name="{{ $data->label }}[]" value="{{ $option }}" id="{{ $data->label }}_{{ titleToKey($option) }}" @checked(in_array($option, old($data->label, [])))>
                        <label for="{{ $data->label }}_{{ titleToKey($option) }}" class="form-check-label">{{ $option }}</label>
                    </div>
                @endforeach
            </div>
        @elseif ($data->type == 'radio')
            <div class="d-flex flex-wrap gap-3">
                @foreach ($data->options as $option)
                    <div class="form--check">
                        <input type="radio" class="form-check-input" name="{{ $data->label }}" value="{{ $option }}" id="{{ $data->label }}_{{ titleToKey($option) }}" @checked($option == old($data->label))>
                        <label for="{{ $data->label }}_{{ titleToKey($option) }}" class="form-check-label">{{ $option }}</label>
                    </div>
                @endforeach
            </div>
        @elseif ($data->type == 'file')
            <input type="file" class="{{ $formControlClass }}" name="{{ $data->label }}" @required($data->is_required == 'required') accept="@foreach (explode(',', $data->extensions) as $ext) {{ ".$ext" }}@if (!$loop->last), @endif @endforeach">
            <pre class="text--base mt-2 mb-0">@lang('Supported mimes'): {{ $data->extensions }}</pre>
        @elseif($data->type == 'email')
            <input type="email" class="{{ $formControlClass }}" name="{{ $data->label }}" value="{{ old($data->label) }}" @required($data->is_required == 'required')>
        @elseif($data->type == 'url')
            <input type="url" class="{{ $formControlClass }}" name="{{ $data->label }}" value="{{ old($data->label) }}" @required($data->is_required == 'required')>
        @elseif($data->type == 'number')
            <input type="number" step="any" min="0" class="{{ $formControlClass }}" name="{{ $data->label }}" value="{{ old($data->label) }}" @required($data->is_required == 'required')>
        @elseif($data->type == 'datetime')
            <input type="datetime-local" class="{{ $formControlClass }}" name="{{ $data->label }}" value="{{ old($data->label) }}" @required($data->is_required == 'required')>
        @elseif($data->type == 'date')
            <input type="date" class="{{ $formControlClass }}" name="{{ $data->label }}" value="{{ old($data->label) }}" @required($data->is_required == 'required')>
        @elseif($data->type == 'time')
            <input type="time" class="{{ $formControlClass }}" name="{{ $data->label }}" value="{{ old($data->label) }}" @required($data->is_required == 'required')>
        @endif
    </div>
@endforeach
