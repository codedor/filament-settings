<div>
    <div>
        {{ __('filament-settings::widget.required fields title') }}
    </div>

    <div>
        @foreach($requiredKeys as $requiredKey)
            {{ $requiredKey }} {{ setting($requiredKey) }}
        @endforeach
    </div>
</div>
