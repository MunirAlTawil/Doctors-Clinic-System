<div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
    {{-- Base wash --}}
    <div class="absolute inset-0 bg-gradient-to-br from-slate-100 via-cyan-50/45 to-slate-200/90"></div>
    {{-- Large diagonal panels --}}
    <div
        class="absolute -left-[25%] top-0 h-[130%] w-[60%] rotate-[14deg] bg-gradient-to-br from-cyan-200/30 via-transparent to-transparent opacity-90"
    ></div>
    <div
        class="absolute -right-[18%] bottom-0 h-[95%] w-[55%] -rotate-[11deg] bg-gradient-to-tl from-violet-200/22 via-transparent to-transparent opacity-75"
    ></div>
    {{-- Fine lines --}}
    <div
        class="absolute inset-0 opacity-[0.038]"
        style="background-image: repeating-linear-gradient(-32deg, transparent, transparent 14px, rgba(6, 182, 212, 0.55) 14px, rgba(6, 182, 212, 0.55) 15px);"
    ></div>
    {{-- Mesh blobs --}}
    <div
        class="absolute -left-1/4 top-0 h-[min(78vh,36rem)] w-[min(96vw,48rem)] animate-mesh-breathe rounded-full bg-gradient-to-br from-cyan-300/38 via-sky-200/20 to-transparent blur-3xl"
    ></div>
    <div
        class="absolute -right-1/4 bottom-0 h-[min(70vh,32rem)] w-[min(92vw,44rem)] animate-mesh-breathe-delayed rounded-full bg-gradient-to-tl from-violet-300/30 via-cyan-100/14 to-transparent blur-3xl"
    ></div>
    <div
        class="absolute left-1/2 top-1/3 h-[min(48vh,22rem)] w-[min(62vw,30rem)] -translate-x-1/2 animate-blob-drift rounded-full bg-gradient-to-r from-teal-200/25 to-cyan-200/14 blur-3xl"
    ></div>
    {{-- Dot grid --}}
    <div
        class="absolute inset-0 opacity-[0.35]"
        style="background-image: radial-gradient(circle at 1px 1px, rgba(15, 23, 42, 0.07) 1px, transparent 0); background-size: 28px 28px;"
    ></div>
    {{-- Soft vignette --}}
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-slate-100/40"></div>
</div>
