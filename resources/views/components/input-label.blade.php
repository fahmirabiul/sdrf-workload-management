@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-[13px] font-semibold text-[#1d1d1f] mb-1.5 tracking-[-0.08px]']) }}>
    {{ $value ?? $slot }}
</label>
