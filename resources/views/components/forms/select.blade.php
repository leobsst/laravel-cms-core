@props(['name', 'id'])

<div>
    <div class="flex flex-col mt-2 {{$attributes->get('class') ?? null}}">
        @if ($attributes->has("label"))
        <label for="{{$name}}" class="global-label">{!! $attributes->get("label") !!}</label>
        @endif

        <select
            id="{{$name}}"
            class="global-input"
            wire:model.debounce.365ms="{{$id}}"
        >
            {{$slot}}
        </select>
    </div>
</div>
