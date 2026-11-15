{{-- GP247-EC order totals: rows on a white panel, the last (grand total) row emphasised in crimson. --}}
@if (!empty($dataTotal))
    <div class="card divide-y divide-ink-100 px-5 py-2">
        @foreach ($dataTotal as $total)
            <div class="flex justify-between py-2 {{ $loop->last ? 'text-base' : 'text-sm' }}">
                <span class="{{ $loop->last ? 'font-bold text-ink-900' : 'text-ink-500' }}">{{ $total['title'] ?? '' }}</span>
                <span class="{{ $loop->last ? 'price text-xl' : 'font-semibold text-ink-800' }}">{{ $total['text'] ?? '' }}</span>
            </div>
        @endforeach
    </div>
@endif
