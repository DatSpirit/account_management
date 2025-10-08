@if ($message)
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 5000)"
        class="mb-4 px-4 py-3 rounded shadow"
        :class="{
            'bg-green-100 text-green-800 border border-green-300': '{{ $type }}' === 'success',
            'bg-red-100 text-red-800 border border-red-300': '{{ $type }}' === 'error'
        }"
    >
        {{ $message }}
    </div>
@endif
