@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-[14px] text-[#30d158] text-center mb-5']) }}>
        {{ $status }}
    </div>
@endif
