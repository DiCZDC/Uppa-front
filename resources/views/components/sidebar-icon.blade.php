@props([
    'icon' => '',
    'iconHover' => '',
])

<span class="relative inline-flex size-4 items-center justify-center">
    <flux:icon
        :icon="$icon"
        class="absolute left-1/2 top-1/2 !size-4 -translate-x-1/2 -translate-y-1/2 opacity-100 transition-opacity [[data-flux-sidebar-item]:hover_&]:opacity-0 [[data-flux-sidebar-item][data-current]_&]:opacity-0"
    />
    <flux:icon
        :icon="$iconHover"
        class="absolute left-1/2 top-1/2 !size-4 -translate-x-1/2 -translate-y-1/2 opacity-0 transition-opacity [[data-flux-sidebar-item]:hover_&]:opacity-100 [[data-flux-sidebar-item][data-current]_&]:opacity-100"
    />
</span>
