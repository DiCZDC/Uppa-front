<x-layouts::app :title="__('Estadísticas')">
 
    {{-- ═══════════════════════════════════════════════════════
         MicoScan — Sección de gráficas / Estadísticas
         CDN: https://cdn.jsdelivr.net/npm/chart.js
    ════════════════════════════════════════════════════════ --}}
 
    <div class="mx-auto w-full max-w-3xl px-5 pt-2 pb-12 space-y-8">
 
        {{-- ── Header ───────────────────────────────────────── --}}
        <div class="flex flex-col items-start gap-3.5 pb-1">
            <div class="text-base text-ink-2">
                {{ __('Panel de control') }}
            </div>
            <div class="inline-flex gap-2 text-ink items-center">
                <flux:icon.file-chart-pie class="size-12" />
                <h1 class="mt-1 font-display text-[28px] font-medium leading-[1.1] tracking-[-0.6px]">
                    {{ __('¡Consulta tus estadísticas!') }}
                </h1>
            </div>
        </div>
 
        {{-- ══════════════════════════════════════════════════
             GRÁFICA 1 — Doughnut: salud general de los hongos
        ═════════════════════════════════════════════════════ --}}
        <div class="rounded-3xl border border-line-2 bg-card p-5 ms-shadow-card">
            <div class="mb-1">
                <div class="inline-flex gap-2 text-ink items-center">
                    <flux:icon.chart-pie class="size-6" />
                    <p class="font-display font-semibold text-lg text-ink">Estado general de hongos</p>
                </div>
                <p class="text-[12px] text-ink-3 mt-0.5">
                    Distribución porcentual de hongos sanos vs. enfermedades detectadas
                </p>
            </div>
            <div class="relative mx-auto w-full" style="max-width:340px;">
                <canvas id="chartDoughnut" aria-label="Gráfica de estado de hongos"></canvas>
            </div>
            {{-- Leyenda personalizada --}}
            <div id="legendDoughnut" class="mt-4 flex flex-wrap gap-2 justify-center text-[12px] text-ink-2"></div>
        </div>
 
        {{-- ══════════════════════════════════════════════════
             GRÁFICA 2 — Barras: especies identificadas
        ═════════════════════════════════════════════════════ --}}
        <div class="rounded-3xl border border-line-2 bg-card p-5 ms-shadow-card">
            <div class="mb-1">
                <div class="inline-flex gap-2 text-ink items-center">
                    <flux:icon.chart-column-big class="size-6" />
                    <p class="font-display font-semibold text-lg text-ink">Especies identificadas</p>
                </div>
                <p class="text-[12px] text-ink-3 mt-0.5">
                    Total de escaneos por especie de hongo
                </p>
            </div>
            <div class="relative w-full" style="height:260px;">
                <canvas id="chartBar" aria-label="Gráfica de barras por especie"></canvas>
            </div>
        </div>
 
        {{-- ══════════════════════════════════════════════════
             GRÁFICA 3 — Líneas: tendencia de enfermedades
        ═════════════════════════════════════════════════════ --}}
        <div class="rounded-3xl border border-line-2 bg-card p-5 ms-shadow-card">
            <div class="mb-1">
                <div class="inline-flex gap-2 text-ink items-center">
                    <flux:icon.chart-no-axes-combined class="size-6" />
                    <p class="font-display font-semibold text-lg text-ink">Tendencia de enfermedades</p>
                </div>
                <p class="text-[12px] text-ink-3 mt-0.5">
                    Frecuencia mensual de enfermedades detectadas (enero – junio 2026)
                </p>
            </div>
            <div class="relative w-full" style="height:260px;">
                <canvas id="chartLine" aria-label="Gráfica de líneas de tendencia de enfermedades"></canvas>
            </div>
        </div>
 
    </div>{{-- /max-w-3xl --}}
 
    {{-- ══════════════════════════════════════════════════════════
         Chart.js CDN (jsDelivr — recomendado por documentación oficial)
    ═══════════════════════════════════════════════════════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
    <script>
    /* ─────────────────────────────────────────────────────────
       Paleta MicoScan extraída de app.css
    ───────────────────────────────────────────────────────── */
    const MS = {
        pink:       '#d45656',
        orange:     '#fba518',
        gold:       '#f9cb43',
        olive:      '#80b67e',
        blue:       '#368ab8',
        grape:      '#3c3546',
        ink2:       '#5a5364',
        ink3:       '#8a8395',
        cream:      '#f6efdb',
        verdeClaro: '#cdf9e3',
        verdeFuerte:'#376939',
        verdeOscuro:'#016630',
 
        /* Versiones con alfa para fondos suaves */
        oliveA20:  'rgba(128,182,126,0.18)',
        blueA20:   'rgba(54,138,184,0.18)',
        pinkA20:   'rgba(212,86,86,0.18)',
        goldA20:   'rgba(249,203,67,0.18)',
        orangeA20: 'rgba(251,165,24,0.18)',
        grapeA20:  'rgba(60,53,70,0.12)',
    };
 
    /* ─────────────────────────────────────────────────────────
       Opciones globales compartidas
    ───────────────────────────────────────────────────────── */
    const FONT_FAMILY = "'Inter', sans-serif";
 
    Chart.defaults.font.family  = FONT_FAMILY;
    Chart.defaults.color        = MS.ink2;
 
    /* Helpers de tooltip */
    const tooltipDefaults = {
        backgroundColor: MS.grape,
        titleColor:      '#ffffff',
        bodyColor:       '#e8e4f0',
        padding:         12,
        cornerRadius:    10,
        titleFont:       { family: FONT_FAMILY, weight: '600', size: 13 },
        bodyFont:        { family: FONT_FAMILY, size: 12 },
        borderColor:     'rgba(255,255,255,0.08)',
        borderWidth:     1,
    };
 
    /* ═══════════════════════════════════════════════════════
       GRÁFICA 1 — Doughnut
    ════════════════════════════════════════════════════════ */
    (function () {
        /*
         * Datos hardcodeados congruentes:
         *   Total escaneos : 248
         *   Sanos          : 163 (65.7 %)
         *   Enfermos       :  85 (34.3 %)
         *     ├─ Burbuja seca (Verticillium)  : 32
         *     ├─ Mancha bacterial             : 22
         *     ├─ Mildiú del champiñón         : 18
         *     └─ Trichoderma verde            : 13
         */
        const labels = [
            'Sanos',
            'Burbuja seca (Verticillium)',
            'Mancha bacterial',
            'Mildiú del champiñón',
            'Trichoderma verde',
        ];
        const dataValues  = [163, 32, 22, 18, 13];
        const bgColors    = [MS.olive, MS.pink, MS.orange, MS.blue, MS.gold];
        const hoverColors = ['#6aa068', '#b83a3a', '#d98810', '#2a6f96', '#d9a830'];
 
        const ctx = document.getElementById('chartDoughnut');
 
        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    label: 'Escaneos',
                    data:  dataValues,
                    backgroundColor:      bgColors,
                    hoverBackgroundColor: hoverColors,
                    hoverOffset:          10,
                    borderWidth:          3,
                    borderColor:          '#ffffff',
                }],
            },
            options: {
                responsive:          true,
                maintainAspectRatio: true,
                cutout:              '62%',
                animation: {
                    animateRotate: true,
                    animateScale:  true,
                    duration:      900,
                    easing:        'easeInOutQuart',
                },
                plugins: {
                    legend: { display: false },   // leyenda propia abajo
                    title: {
                        display:  true,
                        text:     '248 escaneos totales',
                        color:    MS.ink2,
                        font:     { size: 12, family: FONT_FAMILY },
                        padding:  { bottom: 10 },
                    },
                    tooltip: {
                        ...tooltipDefaults,
                        callbacks: {
                            label: (ctx) => {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct   = ((ctx.parsed / total) * 100).toFixed(1);
                                return ` ${ctx.label}: ${ctx.parsed} escaneos (${pct}%)`;
                            },
                        },
                    },
                },
            },
            /* Plugin inline: texto central */
            plugins: [{
                id: 'doughnutCenter',
                afterDraw(chart) {
                    const { ctx, chartArea: { top, bottom, left, right } } = chart;
                    const cx = (left + right)  / 2;
                    const cy = (top  + bottom) / 2;
                    ctx.save();
                    ctx.textAlign    = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle    = MS.grape;
                    ctx.font         = `700 22px ${FONT_FAMILY}`;
                    ctx.fillText('65.7%', cx, cy - 10);
                    ctx.fillStyle = MS.ink3;
                    ctx.font      = `400 11px ${FONT_FAMILY}`;
                    ctx.fillText('hongos sanos', cx, cy + 11);
                    ctx.restore();
                },
            }],
        });
 
        /* Leyenda personalizada */
        const legendEl  = document.getElementById('legendDoughnut');
        const totalScans = dataValues.reduce((a, b) => a + b, 0);
        labels.forEach((label, i) => {
            const pct  = ((dataValues[i] / totalScans) * 100).toFixed(1);
            const item = document.createElement('div');
            item.style.cssText = 'display:flex;align-items:center;gap:6px;cursor:pointer;';
            item.innerHTML = `
                <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:${bgColors[i]};flex-shrink:0;"></span>
                <span style="color:${MS.ink2};">${label} <strong style="color:${MS.grape};">${pct}%</strong></span>
            `;
            /* Toggle al hacer clic */
            item.addEventListener('click', () => {
                const meta = chart.getDatasetMeta(0);
                const arc  = meta.data[i];
                arc.hidden = !arc.hidden;
                item.style.opacity = arc.hidden ? '0.4' : '1';
                chart.update();
            });
            legendEl.appendChild(item);
        });
    })();
 
    /* ═══════════════════════════════════════════════════════
       GRÁFICA 2 — Barras: especies identificadas
    ════════════════════════════════════════════════════════ */
    (function () {
        /*
         * Top-7 especies más escaneadas (datos coherentes con el total de 248)
         */
        const especies = [
            'A. bisporus',      // Champiñón blanco
            'P. ostreatus',     // Ostra / Seta de ostra
            'L. edodes',        // Shiitake
            'C. cibarius',      // Rebozuelo
            'B. edulis',        // Porcini / Boleto
            'M. esculenta',     // Colmenilla
            'F. velutipes',     // Enokitake
        ];
        const totales = [74, 52, 41, 30, 24, 16, 11];
        const bgs = [
            MS.olive, MS.blue, MS.gold, MS.orange, MS.grape, MS.pink, MS.ink3,
        ];
        const hoverBgs = [
            '#6aa068','#2a6f96','#d9a830','#d98810','#2e2738','#b83a3a','#6a6077',
        ];
 
        new Chart(document.getElementById('chartBar'), {
            type: 'bar',
            data: {
                labels: especies,
                datasets: [{
                    label:                'Escaneos totales',
                    data:                 totales,
                    backgroundColor:      bgs,
                    hoverBackgroundColor: hoverBgs,
                    borderRadius:         8,
                    borderSkipped:        false,
                    barThickness:         28,
                }],
            },
            options: {
                responsive:          true,
                maintainAspectRatio: false,
                animation: {
                    duration: 800,
                    easing:   'easeOutBounce',
                    delay:    (ctx) => ctx.dataIndex * 80,
                },
                layout: { padding: { top: 10 } },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text:    'Especie de hongo (nombre abreviado)',
                            color:   MS.ink3,
                            font:    { size: 11, family: FONT_FAMILY },
                            padding: { top: 8 },
                        },
                        grid:  { display: false },
                        ticks: { color: MS.ink2, font: { size: 11 }, maxRotation: 30 },
                    },
                    y: {
                        title: {
                            display: true,
                            text:    'Cantidad de escaneos',
                            color:   MS.ink3,
                            font:    { size: 11, family: FONT_FAMILY },
                            padding: { bottom: 8 },
                        },
                        beginAtZero: true,
                        max:         90,
                        grid:        { color: 'rgba(60,53,70,0.06)' },
                        ticks:       { color: MS.ink3, stepSize: 15, font: { size: 11 } },
                    },
                },
                plugins: {
                    legend: { display: false },
                    title: {
                        display:  true,
                        text:     'Escaneos por especie — acumulado 2026',
                        color:    MS.ink2,
                        font:     { size: 12, family: FONT_FAMILY },
                        padding:  { bottom: 12 },
                    },
                    tooltip: {
                        ...tooltipDefaults,
                        callbacks: {
                            title: (items) => items[0].label,
                            label: (ctx)   => ` ${ctx.parsed.y} escaneos registrados`,
                        },
                    },
                },
            },
        });
    })();
 
    /* ═══════════════════════════════════════════════════════
       GRÁFICA 3 — Líneas: tendencia mensual de enfermedades
    ════════════════════════════════════════════════════════ */
    (function () {
        /*
         * Enero – Junio 2026. Cuatro enfermedades rastreadas.
         * Los datos muestran pico en marzo (temporada húmeda) y
         * descenso hacia mayo tras medidas de control.
         */
        const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'];
 
        const enfermedades = [
            {
                label:       'Burbuja seca (Verticillium)',
                data:        [4, 6, 11, 8, 5, 3],
                borderColor: MS.pink,
                bgColor:     MS.pinkA20,
                pointBg:     MS.pink,
            },
            {
                label:       'Mancha bacterial',
                data:        [2, 4,  8, 7, 4, 2],
                borderColor: MS.orange,
                bgColor:     MS.orangeA20,
                pointBg:     MS.orange,
            },
            {
                label:       'Mildiú del champiñón',
                data:        [1, 3,  6, 5, 2, 1],
                borderColor: MS.blue,
                bgColor:     MS.blueA20,
                pointBg:     MS.blue,
            },
            {
                label:       'Trichoderma verde',
                data:        [1, 2,  4, 3, 2, 1],
                borderColor: MS.gold,
                bgColor:     MS.goldA20,
                pointBg:     MS.gold,
            },
        ];
 
        const datasets = enfermedades.map(e => ({
            label:           e.label,
            data:            e.data,
            borderColor:     e.borderColor,
            backgroundColor: e.bgColor,
            pointBackgroundColor: e.pointBg,
            pointBorderColor:    '#ffffff',
            pointBorderWidth:    2,
            pointRadius:         5,
            pointHoverRadius:    8,
            borderWidth:         2.5,
            tension:             0.38,
            fill:                true,
        }));
 
        new Chart(document.getElementById('chartLine'), {
            type: 'line',
            data: { labels: meses, datasets },
            options: {
                responsive:          true,
                maintainAspectRatio: false,
                interaction: {
                    mode:      'index',
                    intersect: false,
                },
                animation: {
                    duration: 900,
                    easing:   'easeInOutCubic',
                },
                layout: { padding: { top: 10 } },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text:    'Mes (2026)',
                            color:   MS.ink3,
                            font:    { size: 11, family: FONT_FAMILY },
                            padding: { top: 8 },
                        },
                        grid:  { display: false },
                        ticks: { color: MS.ink2, font: { size: 11 } },
                    },
                    y: {
                        title: {
                            display: true,
                            text:    'Casos detectados',
                            color:   MS.ink3,
                            font:    { size: 11, family: FONT_FAMILY },
                            padding: { bottom: 8 },
                        },
                        beginAtZero: true,
                        max:         14,
                        grid:        { color: 'rgba(60,53,70,0.06)' },
                        ticks:       { color: MS.ink3, stepSize: 2, font: { size: 11 } },
                    },
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle:    'circle',
                            padding:       14,
                            font:          { size: 11, family: FONT_FAMILY },
                            color:         MS.ink2,
                        },
                    },
                    title: {
                        display:  true,
                        text:     'Frecuencia mensual de enfermedades — Ene a Jun 2026',
                        color:    MS.ink2,
                        font:     { size: 12, family: FONT_FAMILY },
                        padding:  { bottom: 12 },
                    },
                    tooltip: {
                        ...tooltipDefaults,
                        callbacks: {
                            label: (ctx) =>
                                ` ${ctx.dataset.label}: ${ctx.parsed.y} caso${ctx.parsed.y !== 1 ? 's' : ''}`,
                        },
                    },
                },
            },
        });
    })();
    </script>
 
</x-layouts::app>