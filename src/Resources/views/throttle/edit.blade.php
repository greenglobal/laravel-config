@extends('ggphp-config::layouts.content')

@section('content')

    <div class="header-page">
        <h1 class="title-page">
            Edit throttle
        </h1>
    </div>

    <div class="content-page">

        <form method="POST" action="{{ route('config.throttle.update') }}">

            @csrf()
            <input type="hidden" id="id" name="id" value="{{ $id ?? '' }}">

            <div class="row">
                <div>
                    <label for="max_attempts">Max Attempts<span class="required">*<span></label>
                </div>

                <div>
                  <input type="text" id="maxAttempts" name="max_attempts" value="{{ $throttle['max_attempts'] ?? '' }}" class="input-text">

                  @if($errors->has('max_attempts'))
                      <span class="error">{{ $errors->first('max_attempts') }}</span>
                  @endif
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="decay_minutes">Decay Minutes<span class="required">*<span></label>
                </div>

                <div>
                    <input type="text" id="decayMinutes" name="decay_minutes" value="{{ $throttle['decay_minutes'] ?? '' }}" class="input-text">

                    @if($errors->has('decay_minutes'))
                        <span class="error">{{ $errors->first('decay_minutes') }}</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <input type="submit" value="Save" class="input-submit">
            </div>

        </form>

    </div>

@stop
