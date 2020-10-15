<div>
    <form method="POST" action="" enctype="multipart/form-data">
        @csrf()
        <input name="_method" type="hidden" value="PUT">
        @foreach ($fields as $field)
            <div>
                @if ($field['type'] === 'text')
                    <label for="{{ $field['name'] }}">{{ $field['title'] }}</label>
                    <input type="{{ $field['type'] }}" name="{{ $field['code'] }}" value="{{ isset($field['default']) ? $field['default'] : '' }}"/>
                @elseif ($field['type'] === 'select')

                    <label for="{{ $field['name'] }}">{{ $field['title'] }}</label>
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
                @endif
            </div>
        @endforeach
        <button type="submit">Save</button>
        <button type="reset">Reset</button>
    </form>
</div>
