<div class="mx-auto grid max-w-[1440px] grid-cols-1 gap-4 sm:grid-rows-2">
    @if ($amount_earned)
        <div class="cell flex h-full w-full flex-col">
            <div class="align-center mb-4 flex flex-row justify-between">
                <div class="h-fit font-semibold text-[#666666] dark:text-[#acacac]"> {{ __('pages/private/referred-users.1') }}</div>
            </div>
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <x-button.compiled :uid="uniqid()" :title="__('pages/private/referred-users.2') . ' ' . number_format($amount_earned) . ' ' . '₽'" :url="app(\App\Services\TelegramServices::class)->__getUnderscoreRestrictedTelegramLink('payout', auth()->user()->uuid)" />
                    <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                        {{ __('pages/private/referred-users.3') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="cell mx-auto flex w-full max-w-[1440px] flex-col gap-4">
        <div class="flex flex-col">
            @if ($referred_users->isNotEmpty())
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-[#292929]">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-[#292929]">
                                <thead class="bg-gray-50 dark:bg-[#161616]">
                                    <tr>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-normal text-gray-500 rtl:text-right dark:text-gray-400">
                                            {{ __('pages/private/referred-users.4') }}</th>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-normal text-gray-500 rtl:text-right dark:text-gray-400">
                                            {{ __('pages/private/referred-users.5') }}</th>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-normal text-gray-500 rtl:text-right dark:text-gray-400">
                                            {{ __('pages/private/referred-users.6') }}</th>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-normal text-gray-500 rtl:text-right dark:text-gray-400">
                                            {{ __('pages/private/referred-users.7') }}</th>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-normal text-gray-500 rtl:text-right dark:text-gray-400">
                                            {{ __('pages/private/referred-users.8') }}</th>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-normal text-gray-500 rtl:text-right dark:text-gray-400">
                                            {{ __('pages/private/referred-users.9') }}</th>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-normal text-gray-500 rtl:text-right dark:text-gray-400">
                                            <div class="flex items-center gap-x-3">
                                                <button class="flex items-center gap-x-2">
                                                    <span>{{ __('pages/private/referred-users.10') }}</span>
                                                    @svg('arrow-downward', 'w-4 h-4')
                                                </button>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white dark:divide-[#292929] dark:bg-[#1c1c1c]">

                                    @foreach ($referred_users as $key => $user)
                                        @php
                                            $expires_at = $user->payment?->expires_at;
                                            $updated_at = $user->payment?->updated_at;
                                            $strikes = $user->payment?->strikes;
                                            $amount = $user->payment?->amount;
                                        @endphp

                                        <tr>
                                            <td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">
                                                <div class="inline-flex items-center gap-x-3">
                                                    {{ $user->payment?->invoice_id ?? '-' }}
                                                </div>
                                            </td>

                                            <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $user->payment?->currency ?? '-' }}
                                            </td>

                                            <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $amount ? "{$amount} ₽" : '-' }}
                                            </td>

                                            <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                @if (isset($user->payment))
                                                    {{ "{$strikes}/3" }}
                                                @else
                                                    {{ '-' }}
                                                @endif
                                            </td>

                                            <td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-gray-700">
                                                @if ($user->payment?->status === 'subscription-active')
                                                    <div class="inline-flex items-center gap-x-2 rounded-full bg-green-50 px-3 py-1 text-green-600 ring-1 ring-inset ring-green-600/10 dark:bg-green-400/10 dark:text-green-400 dark:ring-green-400/30">
                                                        @svg('custom.check', 'w-4 h-4')
                                                        <h2 class="text-sm font-normal">{{ __('pages/private/referred-users.11') }}</h2>
                                                    </div>
                                                @elseif($user->payment?->status === 'completed')
                                                    <div class="inline-flex items-center gap-x-2 rounded-full bg-purple-50 px-3 py-1 text-purple-600 ring-1 ring-inset ring-purple-600/10 dark:bg-purple-400/10 dark:text-purple-400 dark:ring-purple-400/30">
                                                        @svg('shield-locked', 'w-4 h-4')
                                                        <h2 class="text-sm font-normal">{{ __('pages/private/referred-users.12') }}</h2>
                                                    </div>
                                                @elseif($user->payment?->status === 'subscription-cancelled')
                                                    <div class="inline-flex items-center gap-x-2 rounded-full bg-red-50 px-3 py-1 text-red-600 ring-1 ring-inset ring-red-600/10 dark:bg-red-400/10 dark:text-red-400 dark:ring-red-400/30">
                                                        @svg('custom.cross', 'w-4 h-4')
                                                        <h2 class="text-sm font-normal">{{ __('pages/private/referred-users.13') }}</h2>
                                                    </div>
                                                @elseif(in_array($user->payment?->status, ['subscription-failed', 'failed']))
                                                    <div class="inline-flex items-center gap-x-2 rounded-full bg-red-50 px-3 py-1 text-red-600 ring-1 ring-inset ring-red-600/10 dark:bg-red-400/10 dark:text-red-400 dark:ring-red-400/30">
                                                        @svg('cancel-schedule-send', 'w-4 h-4')
                                                        <h2 class="text-sm font-normal">{{ __('pages/private/referred-users.14') }}</h2>
                                                    </div>
                                                @elseif($user->payment?->status === 'pending')
                                                    <div class="inline-flex items-center gap-x-2 rounded-full bg-yellow-50 px-3 py-1 text-yellow-600 ring-1 ring-inset ring-yellow-600/10 dark:bg-yellow-400/10 dark:text-yellow-400 dark:ring-yellow-400/30">
                                                        @svg('work-history', 'w-4 h-4')
                                                        <h2 class="text-sm font-normal">{{ __('pages/private/referred-users.16') }}</h2>
                                                    </div>
                                                @else
                                                    <div class="inline-flex items-center gap-x-2 rounded-full bg-gray-50 px-3 py-1 text-gray-600 ring-1 ring-inset ring-gray-600/10 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/30">
                                                        @svg('contract-edit', 'w-4 h-4')
                                                        <h2 class="text-sm font-normal">{{ __('pages/private/referred-users.15') }}</h2>
                                                    </div>
                                                @endif
                                            </td>

                                            <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $expires_at ? date('d-m-Y', strtotime($expires_at)) : '-' }}
                                            </td>

                                            <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $updated_at ? date('d-m-Y', strtotime($updated_at)) : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                    {{ __('app.6') }}
                </div>
            @endif
        </div>
    </div>
</div>
