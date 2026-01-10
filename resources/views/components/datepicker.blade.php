<div x-data 
     x-init="
        new Litepicker({ 
            element: $refs.input,
            format: 'YYYY-MM-DD',
            singleMode: true,
            setup: (picker) => {
                picker.on('selected', (date) => {
                    @this.set('{{ $attributes->wire('model')->value() }}', date.format('YYYY-MM-DD'))
                })
            }
        });
     " 
>
    <input x-ref="input" 
           type="text" 
           class="bg-transparent text-xs border-none focus:ring-0 focus:outline-none w-full placeholder:text-slate-700 text-slate-700"
           placeholder="Date"
           {{ $attributes }}
    >
</div>