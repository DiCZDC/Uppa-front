@props([
    'sidebar' => false,
    'mode' => 'color',
    'showText' => true,
])

@if($sidebar)
    <flux:sidebar.brand name="MicoScan" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-9 items-center justify-center">
            <x-app-logo-icon mode="{{ $mode }}" class="size-9" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <a {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}>
        <x-app-logo-icon mode="{{ $mode }}" class="size-9" />
        @if($showText)
            <span class="font-display text-[20px] font-medium tracking-[-0.4px] text-ink">MicoScan</span>
        @endif
    </a>
@endif
