@props(['name', 'id'])

<div>
    <div class="flex flex-col mt-2 {{$attributes->get('class') ?? null}}">
        @if ($attributes->has("label"))
        <label for="{{$name}}" class="global-label">{!! $attributes->get("label") !!}</label>
        @endif

        <textarea
                wire:model.defer="{{ $id }}"
                class="global-textarea {{ $attributes->has('resize-none') ? 'resize-none' : null }}"
                name="{{ $name }}"
                id="{{ $id }}"
                placeholder="{!! $placeholder ?? '' !!}"
                rows="{{ $attributes->get('rows') ?? 3 }}"
                wire:loading.attr="disabled"
                @if($attributes->get('disabled'))
                    disabled
                @endif
        >
            {{ old($id) }}
        </textarea>
    </div>
</div>
