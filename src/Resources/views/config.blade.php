@extends('ggphp-config::layouts.content')
@section('content')

<div class="content-page">
    @if (count($fields))
        <form method="POST" action="{{ route('config-updates') }}">
            @csrf()
            <input name="_method" type="hidden" value="PUT">
            @foreach ($fields as $field)
                @php
                    $config = $configs->getConfigByCode($field['code']);
                    $value = $config ? $config->value : null;
                @endphp

                <div class="row">
                    @if ($field['type'] === 'text' || $field['type'] === 'number')
                        <label for="{{ $field['code'] }}">{{ $field['title'] }}</label>
                        <input type="{{ $field['type'] }}"
                            name="{{ $field['code'] }}"
                            value="{{ $value ? $value : (isset($field['default']) ? $field['default'] : '') }}"
                            id="{{ $field['code'] }}"
                            class="input-text"/>
                        <p class="error">{{ $errors->first($field['code']) }}</p>
                    @elseif ($field['type'] === 'select')
                        <label for="{{ $field['code'] }}">{{ $field['title'] }}</label>
                        <select name="{{ $field['code'] }}" id="{{ $field['code'] }}" class="select">
                            <option value=""></option>
                            @if (isset($field['options']))
                                @php
                                    $selected = $value ? $value : (isset($field['default']) ? $field['default'] : '')
                                @endphp

                                @foreach ($field['options'] as $option)
                                    <option value="{{ $option['value'] }}" {{ $option['value'] == $selected ? 'selected' : '' }}>
                                        {{ $option['title'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    @elseif ($field['type'] === 'boolean')
                        @php
                            $checked = $value ? $value : (isset($field['default']) ? $field['default'] : '')
                        @endphp
                        <P class="label">{{ $field['name'] }}</P>
                        <label class="switch">
                           <span>
                                <input type="checkbox"
                                    value="{{ isset($field['value']) ? $field['value'] : '' }}"
                                    name="{{ $field['code'] }}"
                                    {{ (isset($field['value']) && $field['value'] == $checked) ? 'checked' : ''}}
                                    id="{{ $field['code'] }}"
                                    class="">
                                <span class="slider round"></span>
                           </span>
                        </label>
                    @endif
                </div>
            @endforeach
            <div class="row">
                <button type="submit" class="input-submit">Save</button>
                <button type="button" onclick="resetDefault();" class="input-submit">Reset to default</button>
            </div>
        </form>
    @else
    <div>
        <p>Chưa có field nào được thiết lập</p>
    </div>
    @endif
</div>

@stop

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function resetDefault() {
        $.ajax({
            type:'GET',
            url:'/config/reset',
            success:function(response) {
                response.data.forEach(value => {
                    $("#" + value.code).val(value.default);
                });
            }
        });
    }
</script>

