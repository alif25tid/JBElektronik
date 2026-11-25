<footer class="bg-slate-900 text-slate-300 border-t border-slate-800">
    <div class="container mx-auto px-4 sm:px-6 py-5">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Brand & Keterangan Panel -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center shrink-0 overflow-hidden">
                    <img src="{{ asset('images/logosaja.png') }}" alt="Logo Jualin" class="w-5 h-5 object-contain">
                </div>
                <div class="text-center sm:text-left leading-tight">
                    <p class="text-xs font-bold tracking-wide text-white">JUALIN PCR &bull; ADMIN PANEL</p>
                    <p class="text-[11px] text-slate-400">Moderasi barang &amp; pengawasan kampus PCR</p>
                </div>
            </div>

            <!-- Copyright & Status -->
            <div class="flex flex-wrap items-center justify-center sm:justify-end gap-x-3 gap-y-1 text-xs text-slate-400">
                <span>&copy; {{ date('Y') }} Jualin PCR</span>
                <span class="text-slate-600">&bull;</span>
                <span class="inline-flex items-center gap-1.5 text-slate-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Sistem Aktif
                </span>
            </div>
        </div>
    </div>
</footer>