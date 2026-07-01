@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full h-[44px] px-4 text-[17px] text-[#1d1d1f] bg-white border border-[#d2d2d7] rounded-[8px] outline-none transition-all duration-200 ease-in-out focus:border-[#0071e3] focus:shadow-[0_0_0_3px_rgba(0,113,227,0.15)] placeholder-[#aeaeb2]']) !!}>
