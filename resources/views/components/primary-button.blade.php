<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-[#0066cc] border border-transparent rounded-[11px] font-semibold text-[15px] text-white tracking-[-0.08px] hover:bg-[#005bb5] focus:bg-[#005bb5] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-[#0071e3] focus:ring-offset-2 transition-all duration-200 ease-in-out']) }}>
    {{ $slot }}
</button>
