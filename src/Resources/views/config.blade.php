@extends('ggphp-config::layouts.content')

@section('content')

    <div class="header-page">
        <h1 class="title-page">
            {{ $data['name'] ?? '' }}
        </h1>
    </div>

    <div class="content-page">

        @if(Session::has('message'))
            <p class="alert alert-success">{{ Session::get('message') }}<span class="close-alert" onclick="this.parentElement.style.display='none';">x</span></p>
        @endif

        @if (! empty($data['fields']) && count($data['fields']))
            <form method="POST" action="{{ route('config.field.update') }}">

                @csrf()
                <input name="_method" type="hidden" value="PATCH">

                @foreach ($data['fields'] as $field)
                    @php
                        $config = env('STORE_DB', 'database') == 'database'
                            ? getConfigByCode($field['code'])
                            : app('GGPHP\Config\Services\FirebaseService')->getDataByCode($field['code']);
                        $value = $config['value'] ?? null;
                    @endphp

                    @if (isset($field['access']) && $field['access'] == $userRole)
                        <div class="row">

                            @if ($field['type'] == 'text' || $field['type'] == 'number')
                                <label for="{{ $field['code'] }}">{{ $field['title'] ?? '' }}</label>
                                <input class="input-text" type="{{ $field['type'] }}" name="{{ $field['code'] }}" id="{{ $field['code'] }}"
                                    value="{{ $value ? $value : (isset($field['default']) ? $field['default'] : '') }}"
                                />
                                <p class="error">{{ $errors->first($field['code']) }}</p>
                            @elseif ($field['type'] == 'select')
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
                            @elseif ($field['type'] == 'boolean')
                                @php
                                    $checked = $value ? $value : (isset($field['default']) ? $field['default'] : '');
                                @endphp

                                <label class="label">{{ $field['title'] }}</label>
                                <label class="switch">
                                    <input type="checkbox" name="{{ $field['code'] }}" id="{{ $field['code'] }}"
                                        {{ (isset($field['value']) && $field['value'] == $checked) ? 'checked' : ''}}
                                        value="{{ isset($field['value']) ? $field['value'] : '' }}"
                                    />
                                    <span class="slider round"></span>
                                </label>
                            @endif

                        </div>
                    @endif

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
            url:'/configuration/field/reset',
            success:function(response) {
                response.data.forEach(value => {
                    if (value.type == 'boolean') {
                        $("#code-4").prop("checked", value.default == true ? true : false);
                    } else {
                        $("#" + value.code).val(value.default);
                    }
                });
            }
        });
    }
</script>
