<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Admin Try Dashboard page') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    </x-slot:headerFiles>

    <div>
        <h1>Dashboard page</h1>
    </div>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.dashboard') => '',
        ]"
    />

    <div class="row mt-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('general.menu.furlough_balance_management.title') }}</h5>
                </div>
                <div class="card-body p-2">
                    @php
                        $balances = \App\Models\FurloughBalance::where('user_id', auth()->id())
                            ->with('furloughType')
                            ->get();
                    @endphp

                    @if($balances->isEmpty())
                        <div class="text-muted small">No furlough balances found.</div>
                    @else
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Type') }}</th>
                                    <th class="text-center">{{ __('Total') }}</th>
                                    <th class="text-center">{{ __('Used') }}</th>
                                    <th class="text-center">{{ __('Remaining') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($balances as $balance)
                                    <tr>
                                        <td>{{ $balance->furloughType?->localeName ?? __('-') }}</td>
                                        <td class="text-center">{{ $balance->total_days }}</td>
                                        <td class="text-center">{{ $balance->used_days }}</td>
                                        <td class="text-center">{{ $balance->remaining_days }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-base-layout>