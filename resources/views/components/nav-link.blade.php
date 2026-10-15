@props(['href', 'icon', 'active' => false, 'badge' => null])
<a href="{{ $href }}"
   @class([
       'flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg transition-all duration-150',
       'bg-ums-blue text-white shadow-sm' => $active,
       'text-slate-300 hover:text-white hover:bg-slate-800/80' => ! $active,
   ])>
    <x-icon :name="$icon" :class="$active ? 'text-amber-300' : 'text-slate-400'" />
    <span>{{ $slot }}</span>
    @if ($badge !== null)
        <span class="ml-auto bg-amber-400 text-slate-950 text-xs px-2 py-0.5 font-bold rounded-full">{{ $badge }}</span>
    @endif
</a>
