{{-- blade --}}
@include('components.logout-button.element')

@once
    {{-- javascript --}}
    @push('components.scripts')
        <script src="{{ Vite::asset('resources/views/components/logout-button/element.mjs') }}" type="module"></script>
    @endPush
@endonce
