<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-h-[2.75rem] items-center justify-center gap-2 rounded-xl border border-transparent bg-gradient-to-r from-rose-600 to-rose-700 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-900/20 transition-all duration-300 hover:from-rose-500 hover:to-rose-600 hover:shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 focus-visible:ring-offset-2 active:scale-[0.98] disabled:pointer-events-none disabled:opacity-50']) }}>
    {{ $slot }}
</button>
