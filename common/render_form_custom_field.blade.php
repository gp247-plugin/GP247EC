{{--
    Shared custom-field renderer for the register and change-information forms.
    Included directly by path, so every template must ship it.

    WHY markup is written here instead of calling gp247_form_render_field(): that
    core helper emits Bootstrap classes, which have no styling on this Tailwind
    storefront (checkbox / radio layout breaks). Only value escaping is reused from
    core (gp247_form_render_escape) to keep output encoding consistent.

    Variables:
    - $object: model instance (or []) passed by the including view
    - $customFieldType: optional table name used when no model is bound (register
      form), so the field type is still known and required fields render.
--}}
@php
    // WHY: registration binds no model ($object = []), so getTable() cannot supply
    // the EAV type; the including view passes it as $customFieldType. This is the
    // render half of the register POST validator's contract (customer.php always
    // validates customer custom fields). A bound model still wins for profile/admin,
    // where $customFieldType is inert.
    $type = is_object($object) ? $object->getTable() : ($customFieldType ?? '');
    $fields = is_object($object) ? $object->getCustomFields() : [];
    $customFields = gp247_custom_field_list($type);
@endphp

@if (!empty($customFields) && is_countable($customFields) && count($customFields))
    <div class="divider my-4"></div>
    <p class="label mb-3">
        {{ gp247_language_render('admin.custom_field.title') }}
    </p>
    @foreach ($customFields as $keyField => $field)
        @php
            $code = $field->code;
            $hasError = $errors->has('fields.'.$code);
            $inputClass = 'input '.($hasError ? 'input-error ' : '').$code;
            // WHY: $field->default holds the options JSON for select/radio/checkbox;
            // decodes to null for plain-text fields (then treated as no options).
            $options = json_decode($field->default, true);
            $options = is_array($options) ? $options : [];
            $current = old('fields.'.$code, $fields[$code]['text'] ?? '');
            $isCheckbox = $field->option === 'checkbox';
            $inputName = $isCheckbox ? 'fields['.$code.'][]' : 'fields['.$code.']';
            // WHY: old() repopulates a checkbox group as an array; stored value is a CSV string.
            $checkedValues = $isCheckbox ? (is_array($current) ? $current : explode(',', (string) $current)) : [];
            $currentScalar = is_array($current) ? '' : (string) $current;
            $required = (int) $field->required === 1;
        @endphp
        <div class="mb-4">
            <label class="label" for="{{ $keyField }}">{{ gp247_language_render($field->name) }}</label>

            @switch($field->option)
                @case('textarea')
                    <textarea id="{{ $keyField }}" name="{{ $inputName }}" rows="3" @required($required)
                        class="{{ $inputClass }}">{!! gp247_form_render_escape($currentScalar) !!}</textarea>
                    @break

                @case('select')
                    <select id="{{ $keyField }}" name="{{ $inputName }}" @required($required) class="{{ $inputClass }}">
                        <option value=""></option>
                        @foreach ($options as $optKey => $optLabel)
                            <option value="{!! gp247_form_render_escape($optKey) !!}" @selected($currentScalar == $optKey)>{!! gp247_form_render_escape($optLabel) !!}</option>
                        @endforeach
                    </select>
                    @break

                @case('radio')
                    <div class="flex flex-wrap gap-x-4 gap-y-2 pt-1">
                        @foreach ($options as $optKey => $optLabel)
                            <label for="{{ $keyField }}__{{ $loop->index }}" class="flex items-center gap-1.5 text-sm text-ink-600 cursor-pointer whitespace-nowrap">
                                <input id="{{ $keyField }}__{{ $loop->index }}" type="radio" name="{{ $inputName }}"
                                    value="{!! gp247_form_render_escape($optKey) !!}" @checked($currentScalar == $optKey)
                                    class="border-ink-300 accent-brand-600">
                                {!! gp247_form_render_escape($optLabel) !!}
                            </label>
                        @endforeach
                    </div>
                    @break

                @case('checkbox')
                    <div class="flex flex-wrap gap-x-4 gap-y-2 pt-1">
                        @foreach ($options as $optKey => $optLabel)
                            <label for="{{ $keyField }}__{{ $loop->index }}" class="flex items-center gap-1.5 text-sm text-ink-600 cursor-pointer whitespace-nowrap">
                                <input id="{{ $keyField }}__{{ $loop->index }}" type="checkbox" name="{{ $inputName }}"
                                    value="{!! gp247_form_render_escape($optKey) !!}" @checked(in_array((string) $optKey, $checkedValues))
                                    class="rounded border-ink-300 accent-brand-600">
                                {!! gp247_form_render_escape($optLabel) !!}
                            </label>
                        @endforeach
                    </div>
                    @break

                @default
                    {{-- text, number, date, month, week, time, email, password, url, color --}}
                    <input id="{{ $keyField }}" type="{{ $field->option ?: 'text' }}" name="{{ $inputName }}"
                        value="{!! gp247_form_render_escape($currentScalar) !!}" @required($required) class="{{ $inputClass }}">
            @endswitch

            @if ($hasError)
                <p class="text-xs text-red-600 mt-1">{{ $errors->first('fields.'.$code) }}</p>
            @endif
        </div>
    @endforeach
@endif
