@props([
    'icon' => '',
    'iconHover' => '',
    'ruta' => '',
    'texto' => '',
])

@php
    $isCurrent = request()->routeIs($ruta);
    $iconToUse = $icon;

    if (is_string($icon) && $icon !== '' && is_string($iconHover) && $iconHover !== '') {
        $iconToUse = new \Illuminate\Support\HtmlString(
            view('components.sidebar-icon', [
                'icon' => $icon,
                'iconHover' => $iconHover,
            ])->render()
        );
    }
@endphp

<flux:sidebar.item
    class="h-8.5! border transition duration-600 ease-in-out border-none!
        hover:bg-golden-pollen! 
        hover:translate-x-2.5 
        hover:text-vintage-grape!
        dark:hover:bg-white/7! dark:hover:!text-white
        data-current:bg-golden-pollen! data-current:text-vintage-grape! data-current:border-zinc-200!
        hover:data-current:text-vintage-grape!

     [&_[data-flux-icon]]:!size-4"
    :icon="$iconToUse"
    :href="$isCurrent ? null : route($ruta)"
    :current="$isCurrent"
    :wire:navigate="!$isCurrent"
>
    {{ __($texto) }}
</flux:sidebar.item>