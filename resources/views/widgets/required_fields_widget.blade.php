<div>
    <div>
        {{ __('filament-settings::widget.required fields title') }}
    </div>

    <div>
        @foreach($requiredKeys as $requiredKey)
            <div>
                {{ $requiredKey }} - {{ setting($requiredKey) ? "OK" : "Needs check!" }}
            </div>
        @endforeach
    </div>
</div>
