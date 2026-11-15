{{--
    Checkout total-method zone: renders the fragment of every active total-method
    plugin (coupon, points…) found by CheckoutWizard::loadTotalPlugins().
    Plugins plug in via CheckoutTotalMethod::checkoutView().

    Each fragment renders inside try/report, so one broken plugin never takes the
    checkout down. Runs inside the CheckoutWizard component, so a fragment's
    wire:model="totalPayload.<key>.*" / wire:click="applyTotal('<key>')" bind directly.

    Variables: $totalPlugins (key => ['info' => getInfo(), 'view' => fragment]),
    $totalMessages, $totalPayload.
--}}
@if (!empty($totalPlugins))
    <div class="space-y-3">
        @foreach ($totalPlugins as $totalKey => $totalPlugin)
            @if (!empty($totalPlugin['view']) && view()->exists($totalPlugin['view']))
                @php
                    try {
                        echo $__env->make($totalPlugin['view'], [
                            'pluginKey' => $totalKey,
                            'plugin'    => $totalPlugin['info'] ?? [],
                            'message'   => $totalMessages[$totalKey] ?? null,
                        ])->render();
                    } catch (\Throwable $e) {
                        report($e);
                    }
                @endphp
            @endif
        @endforeach
    </div>
@endif
