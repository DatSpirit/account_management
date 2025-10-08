@if ($message)
<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 5000)"
    :class="type === 'success'
        ? 'mb-4 px-4 py-3 rounded shadow bg-green-100 text-green-800 border border-green-300'
        : 'mb-4 px-4 py-3 rounded shadow bg-red-100 text-red-800 border border-red-300'"
>
    {{ $message }}
</div>
@endif
