<div class="mx-auto grid max-w-[1440px] grid-cols-1 gap-4 sm:grid-cols-2">

    {{-- Telegram link for dropshipping --}}
    <div class="cell flex h-full w-full flex-col">
        <div class="align-center mb-4 flex flex-row justify-between">
            <div class="h-fit font-semibold text-[#666666] dark:text-[#acacac]"> {{ __('pages/private/dashboard.1') }}</div>
        </div>
        <div class="flex flex-col gap-4">
            @if ($telegram_invite_url)
                <div class="flex flex-col gap-2">
                    <x-inputs.copy-input.compiled :uid="uniqid()" :inscription="__('pages/private/dashboard.19')" attribute="klub-link" :value="$telegram_invite_url" color="orange" />
                    <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                        {{ __('pages/private/dashboard.2') }}
                    </div>
                    <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                        {{ __('pages/private/dashboard.3') }}
                    </div>
                </div>
            @endif
            <div class="flex flex-col gap-2">
                <x-button.compiled :uid="uniqid()" :title="__('pages/private/dashboard.4')" url="https://t.me/+vCvPSbnbDtA2ZTJi" />
                <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                    {{ __('pages/private/dashboard.5') }}
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <x-button.compiled :uid="uniqid()" :title="__('pages/private/dashboard.6')" :url="app(\App\Services\TelegramServices::class)->__getUnderscoreRestrictedTelegramLink('get-archives', auth()->user()->uuid)" />
                <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                    {{ __('pages/private/dashboard.7') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Referral Program --}}
    <div class="cell flex h-full w-full flex-col">
        <div class="align-center mb-4 flex flex-row justify-between">
            <div class="h-fit font-semibold text-[#666666] dark:text-[#acacac]">{{ __('pages/private/dashboard.28') }}</div>
        </div>
        <div class="mb-4 flex flex-col gap-4 sm:mb-[2.2rem]">
            <div class="flex flex-col gap-2">
                <?php $url = url('/') . '?referred_by_uuid=' . auth()->user()->referral_uuid; ?>
                <x-inputs.copy-input.compiled :uid="uniqid()" :inscription="__('pages/private/dashboard.20')" attribute="referral-link" value="{{ $url }}" color="orange" />
                <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                    {{ __('pages/private/dashboard.26') }}
                </div>
                <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                    {{ __('pages/private/dashboard.27') }}
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <div class="flex flex-col gap-4 xl:flex-row">
                <x-field.compiled :uid="uniqid()" :inscription="__('pages/private/dashboard.30')" attribute="subscribers" :value="$activeReferred" color="green" />
                <x-field.compiled :uid="uniqid()" :inscription="__('pages/private/dashboard.31')" attribute="referred-partners" :value="$totalReferred" color="blue" />
                <x-button.compiled :uid="uniqid()" :title="__('pages/private/dashboard.29')" :url="route('private.referred.users')" target="" />
            </div>
            <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                {{ __('pages/private/dashboard.8') }}
            </div>
        </div>
    </div>

    {{-- Status --}}
    <div class="cell flex h-full w-full flex-col">
        <div class="align-center mb-4 flex flex-row justify-between">
            <div class="h-fit font-semibold text-[#666666] dark:text-[#acacac]">{{ __('pages/private/dashboard.12') }}</div>
        </div>
        <div class="flex flex-col gap-4">
            @if (auth()->user()->telegram_channel_exempted)
                <div class="flex flex-col gap-2">
                    <x-field.compiled :uid="uniqid()" :inscription="__('pages/private/dashboard.22')" attribute="remaining-days" value="{{ auth()->user()->days_left }} {{ __('pages/private/dashboard.21') }}" color="green" />
                    <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                        {{ __('pages/private/dashboard.13') }}
                    </div>
                    <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                        {{ __('pages/private/dashboard.14') }}
                    </div>
                </div>
            @else
                <x-field.compiled :uid="uniqid()" :inscription="__('pages/private/dashboard.22')" attribute="remaining-days" value="{{ auth()->user()->days_left }} {{ __('pages/private/dashboard.21') }}" color="green {{ auth()->user()->days_left === 3 ? 'b-text_yellow' : (auth()->user()->days_left === 2 ? 'b-text_orange' : (auth()->user()->days_left === 1 ? 'b-text_red' : 'b-text_green')) }}" />
                <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                    {{ __('pages/private/dashboard.15') }}
                </div>
                <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                    {{ __('pages/private/dashboard.16') }}
                    <u>
                        <a href="{{ route('private.profile') }}">
                            {{ __('pages/private/dashboard.17') }}
                        </a>
                    </u>
                </div>
            @endif
        </div>
    </div>
</div>
