<div class="mx-auto grid max-w-[1440px] grid-cols-1 gap-4 sm:grid-cols-2">

    {{-- Autopayment Settings --}}
    @if ($condition)
        <div class="cell flex h-full w-full flex-col gap-4">
            <div class="h-fit font-semibold text-[#666666] dark:text-[#acacac]"> {{ __('pages/private/profile.4') }}</div>
            <div class="flex flex-col gap-4">
                <x-field.compiled :uid="uniqid()" :inscription="__('pages/private/profile.26')" attribute="payment-status" :value="$status" />
                <x-field.compiled :uid="uniqid()" :inscription="__('pages/private/profile.27')" attribute="payment-amount" :value="$payment->amount" />
                <x-field.compiled :uid="uniqid()" :inscription="__('pages/private/profile.28')" attribute="next-payment-date" :value="$formatted" />
                <div class="flex flex-col gap-2">
                    <x-button.compiled :uid="uniqid()" :title="__('pages/private/profile.20')" :url="app(\App\Services\TelegramServices::class)->__getUnderscoreRestrictedTelegramLink('cancel-subscription', auth()->user()->uuid)" />
                    <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                        {{ __('pages/private/profile.5') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- General Information --}}
    <div class="cell flex h-full w-full flex-col gap-4">
        <div class="h-fit font-semibold text-[#666666] dark:text-[#acacac]"> {{ __('pages/private/profile.23') }}</div>
        <x-inputs.copy-input.compiled :uid="uniqid()" :inscription="__('pages/private/profile.22')" attribute="current-telegram" :value="'t.me/' . auth()->user()->username" />
        <x-field.compiled :uid="uniqid()" :inscription="__('pages/private/profile.21')" attribute="current-name" :value="auth()->user()->name" />
        <x-field.compiled :uid="uniqid()" :inscription="__('pages/private/profile.17')" attribute="current-email" :value="auth()->user()->email" />
        <x-button.compiled :uid="uniqid()" :title="__('pages/private/profile.16')" :url="app(\App\Services\TelegramServices::class)->__getUnderscoreRestrictedTelegramLink('change-email', auth()->user()->uuid)" />
        <x-field.compiled :uid="uniqid()" :inscription="__('pages/private/profile.11')" attribute="email-verified" :value="__($isVerifiedLabel)" />
    </div>

    {{-- Image Settings --}}
    <div class="cell flex h-full w-full flex-col gap-4">
        <div class="h-fit font-semibold text-[#666666] dark:text-[#acacac]"> {{ __('pages/private/profile.1') }}</div>
        <form class="flex flex-col gap-2" id="js-update-image-form">
            <x-inputs.submit-input.compiled :uid="uniqid()" :inscription="__('pages/private/profile.2')" attribute="image-index" />
            <div class="text-[0.8rem] font-normal text-[#acacac] dark:text-[#666666]">
                {{ __('pages/private/profile.9') }}
            </div>
        </form>
    </div>

    {{-- Passsword Settings --}}
    <div class="cell flex h-full w-full flex-col gap-4">
        <div class="h-fit font-semibold text-[#666666] dark:text-[#acacac]">
            {{ __('pages/private/profile.8') }}
        </div>
        <x-button.compiled :uid="uniqid()" :title="__('pages/private/profile.3')" :url="route('public.password.forgot')" />
    </div>
</div>
