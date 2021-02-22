@extends('ggphp-config::layouts.content')

@section('content')

    <div class="header-page">
        <h1 class="title-page">
            API Throttles
        </h1>

        <div class="set-default">
            <a href="{{ route('config.throttle.reset') }}">Set Default</a>
        </div>
    </div>

    <div class="content-page">

        <table>
            <tr>
                <th>Name</th>
                <th>Path</th>
                <th>Max Attempts</th>
                <th>Decay Minutes</th>
                <th></th>
            </tr>

            @foreach ($throttles as $throttle)
                <tr>
                    <td>{{ $throttle['name'] ?? '' }}</td>
                    <td>{{ $throttle['path'] ?? '' }}</td>
                    <td>{{ $throttle['data']['max_attempts'] ?? $throttle['throttleDefault']['max_attempts'] }}</td>
                    <td>{{ $throttle['data']['decay_minutes'] ?? $throttle['throttleDefault']['decay_minutes'] }}</td>
                    <td>
                        <a href="{{ route('config.throttle.edit', $throttle['name']) }}" >Edit</a>
                    </td>
                </tr>
            @endforeach
        </table>

    </div>

@stop
