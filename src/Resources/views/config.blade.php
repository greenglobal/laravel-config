<div>
    <form method="POST" action="{{ route('config-updates') }}">
        @csrf()
        <input name="_method" type="hidden" value="PUT">
        @foreach ($fields as $field)
            <div>
                @if ($field['type'] === 'text')
                    <label for="{{ $field['code'] }}">{{ $field['title'] }}</label>
                    <input type="{{ $field['type'] }}" name="{{ $field['code'] }}" value="{{ isset($field['default']) ? $field['default'] : '' }}"/>
                @elseif ($field['type'] === 'select')
                    <label for="{{ $field['code'] }}">{{ $field['title'] }}</label>
                    <select name="{{ $field['code'] }}">
                        <option value=""></option>
                        @if (isset($field['options']))
                            @foreach ($field['options'] as $option)
                                <option value="{{ $option['value'] }}" {{ isset($field['default']) && $option['value'] == $field['default'] ? 'selected' : '' }}>
                                    {{ $option['title'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                @elseif ($field['type'] === 'boolean')
                    <label class="switch">
                        {{ $field['name'] }}
                        <input type="checkbox" value="1" name="{{ $field['code'] }}">
                        <span class="slider round"></span>
                  </label>
                @endif
            </div>
        @endforeach
        <button type="submit">Save</button>
        <button type="reset">Reset</button>
    </form>
</div>
