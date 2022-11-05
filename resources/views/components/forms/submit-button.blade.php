<div>
    <div class="row">
        <div wire:loading.remove wire:target="{{ $target }}">
            <button type="submit" class="btn btn-primary {{ $buttonClass }}" wire:offline.attr="disabled">
                @if ($icon)
                    <i class="{{ $icon }} me-2"></i>{{ $title }}
                @else
                    {{ $title }}
                @endif
            </button>
        </div>
        <div wire:loading wire:target="{{ $target }}">
            <button class="btn btn-primary {{ $buttonClass }}" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
            </button>
        </div>
    </div>
</div>
