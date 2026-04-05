@props(['current', 'total', 'labels' => []])

<div class="mb-8">
    <div class="flex items-center justify-between relative">
        <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 rounded-full z-0"></div>
        <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-primary rounded-full z-0 transition-all duration-500 ease-in-out" style="width: {{ (($current - 1) / ($total - 1)) * 100 }}%;"></div>
        
        @for ($i = 1; $i <= $total; $i++)
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300 {{ $i <= $current ? 'bg-primary text-white shadow-md' : 'bg-gray-200 text-gray-500' }}">
                    {{ $i }}
                </div>
                @if(isset($labels[$i-1]))
                    <span class="absolute top-10 text-xs font-medium {{ $i <= $current ? 'text-primary' : 'text-gray-400' }} text-center whitespace-nowrap">
                        {{ $labels[$i-1] }}
                    </span>
                @endif
            </div>
        @endfor
    </div>
</div>
