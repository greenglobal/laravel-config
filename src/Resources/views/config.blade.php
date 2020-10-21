<div>
    @if (count($fields))
        <form method="POST" action="{{ route('config-updates') }}">
            @csrf()
            <input name="_method" type="hidden" value="PUT">
            @foreach ($fields as $field)
                @php
                    $config = $configs->getConfigOf($field['code']);
                    $value = $config ? $config->value : null;
                @endphp

                <div>
                    @if ($field['type'] === 'text' || $field['type'] === 'number')
                        <label for="{{ $field['code'] }}">{{ $field['title'] }}</label>
                        <input type="{{ $field['type'] }}"
                            name="{{ $field['code'] }}"
                            value="{{ $value ? $value : (isset($field['default']) ? $field['default'] : '') }}"/>
                        <p style="color:red">{{ $errors->first($field['code']) }}</p>
                    @elseif ($field['type'] === 'select')
                        <label for="{{ $field['code'] }}">{{ $field['title'] }}</label>
                        <select name="{{ $field['code'] }}">
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

                        <label class="switch">
                            {{ $field['name'] }}
                        <input type="checkbox"
                            value="{{ $field['value'] }}"
                            name="{{ $field['code'] }}"
                            {{ $field['value'] == $checked ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                    @endif
                </div>
            @endforeach
            <button type="submit">Save</button>
            <button type="reset">Reset</button>
        </form>
    @else
    <div>
        <p>Chưa có field nào được thiết lập</p>
    </div>
    @endif
</div>
