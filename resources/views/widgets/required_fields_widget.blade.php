<div>
    <div>
        {{ __('filament-settings::widget.required fields title') }}
    </div>

    <div>
        @foreach($requiredKeys as $requiredKey)
            <div>
                {{ $requiredKey }} - {{ setting($requiredKey) ? __('filament-settings::widget.setting ok') : __('filament-settings::widget.setting needs check') }}
            </div>
        @endforeach
    </div>
</div>
