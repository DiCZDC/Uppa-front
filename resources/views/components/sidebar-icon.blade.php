@props([
    'icon' => '',
    'iconHover' => '',
])

<span class="relative inline-flex size-4 items-center justify-center">
    <flux:icon
        :icon="$icon"
        class="absolute left-1/8 top-1/7 !size-4 opacity-100 transition-opacity [[data-flux-sidebar-item]:hover_&]:opacity-0 [[data-flux-sidebar-item][data-current]_&]:opacity-0"
    />
    <flux:icon
        :icon="$iconHover"
        class="absolute left-1/12 top-1/7 !size-4 opacity-0 transition-opacity [[data-flux-sidebar-item]:hover_&]:opacity-100 [[data-flux-sidebar-item][data-current]_&]:opacity-100"
    />
</span>
