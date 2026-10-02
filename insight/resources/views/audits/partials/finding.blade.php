<article class="bg-white border border-stone-300 rounded-xl p-4">
    <p class="text-xs text-clay mb-1">{{ $finding['severity_label'] }} · {{ $finding['category_label'] }}</p>
    <h3 class="font-bold mb-2">{{ $finding['title'] }}</h3>
    <p class="text-sm leading-7 mb-2">{{ $finding['business_impact'] }}</p>
    <p class="text-sm leading-7"><span class="font-semibold">پیشنهاد: </span>{{ $finding['recommendation'] }}</p>
    @if ($finding['page_url'])
        <p class="text-xs text-stone-500 mt-2 break-all">{{ $finding['page_url'] }}</p>
    @endif
</article>
