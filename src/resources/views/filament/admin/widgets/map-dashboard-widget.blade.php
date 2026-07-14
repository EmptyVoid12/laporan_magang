<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-5">
            <div class="flex flex-col gap-4 border-b border-slate-100 pb-4 dark:border-white/10 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Peta Hotspot Gangguan Perangkat</h3>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                        Titik diambil dari koordinat perangkat, jadi operator tidak perlu share location manual saat membuat laporan.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-red-100 bg-red-50 px-4 py-3 dark:border-red-500/20 dark:bg-red-500/10">
                        <div class="text-[11px] font-semibold uppercase tracking-wide text-red-600 dark:text-red-300">Hotspot Teratas</div>
                        <div class="mt-1 text-sm font-bold text-slate-900 dark:text-white">
                            {{ $topHotspot['nama_perangkat'] ?? 'Belum ada riwayat laporan' }}
                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $topHotspot['total_laporan'] ?? 0 }} laporan
                        </div>
                    </div>

                    <div class="rounded-xl border border-sky-100 bg-sky-50 px-4 py-3 dark:border-sky-500/20 dark:bg-sky-500/10">
                        <div class="text-[11px] font-semibold uppercase tracking-wide text-sky-700 dark:text-sky-300">Titik Terpetakan</div>
                        <div class="mt-1 text-sm font-bold text-slate-900 dark:text-white">{{ $mappedDeviceCount }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Perangkat yang sudah memiliki latitude dan longitude</div>
                    </div>

                    <div class="rounded-xl border border-amber-100 bg-amber-50 px-4 py-3 dark:border-amber-500/20 dark:bg-amber-500/10">
                        <div class="text-[11px] font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-300">
                            {{ $hasHotspotData ? 'Titik Aktif' : 'Hotspot Tersedia' }}
                        </div>
                        <div class="mt-1 text-sm font-bold text-slate-900 dark:text-white">{{ $hasHotspotData ? $activeHotspotCount : $hotspotCount }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $hasHotspotData ? 'Masih memiliki aduan yang belum selesai' : 'Akan terisi otomatis setelah laporan gangguan masuk' }}
                        </div>
                    </div>
                </div>
            </div>

            @if ($mappedDeviceCount === 0)
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center dark:border-white/10 dark:bg-slate-900/60">
                    <p class="text-sm font-semibold text-slate-700 dark:text-white">Peta belum bisa ditampilkan.</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Pastikan data perangkat sudah memiliki `latitude` dan `longitude`.
                    </p>
                </div>
            @else
                <div
                    x-data="{
                        hotspots: @js($hotspots),
                        heatmapData: @js($heatmapData),
                        mapCenter: @js($mapCenter),
                        loadError: false,
                        init() {
                            this.ensurePingStyle();
                            this.ensureLeaflet()
                                .then(() => this.$nextTick(() => this.initMap()))
                                .catch(() => {
                                    this.loadError = true;
                                });
                        },
                        ensurePingStyle() {
                            if (document.getElementById('hotspot-ping-style')) {
                                return;
                            }

                            const style = document.createElement('style');
                            style.id = 'hotspot-ping-style';
                            style.textContent = '@keyframes hotspot-ping { 75%, 100% { transform: scale(2); opacity: 0; } }';
                            document.head.appendChild(style);
                        },
                        ensureLeaflet() {
                            if (window.L && typeof window.L.heatLayer === 'function') {
                                return Promise.resolve();
                            }

                            if (window.leafletAssetsLoading) {
                                return window.leafletAssetsLoading;
                            }

                            const cssCandidates = [
                                '/leaflet/leaflet.css',
                                'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
                            ];
                            const jsCandidates = [
                                '/leaflet/leaflet.js',
                                'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
                            ];
                            const heatCandidates = [
                                '/leaflet/leaflet-heat.js',
                                'https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js',
                            ];

                            window.leafletAssetsLoading = this.loadStylesheet(cssCandidates, 'leaflet-runtime-css')
                                .then(() => this.loadScript(jsCandidates, 'leaflet-runtime-js'))
                                .then(() => this.loadScript(heatCandidates, 'leaflet-runtime-heat'))
                                .then(() => {
                                    if (!window.L || typeof window.L.heatLayer !== 'function') {
                                        throw new Error('Leaflet assets failed to initialize.');
                                    }
                                });

                            return window.leafletAssetsLoading;
                        },
                        loadStylesheet(candidates, id) {
                            const existing = document.getElementById(id);
                            if (existing) {
                                return Promise.resolve();
                            }

                            return new Promise((resolve, reject) => {
                                const tryIndex = (index) => {
                                    if (index >= candidates.length) {
                                        reject(new Error('Unable to load stylesheet.'));
                                        return;
                                    }

                                    const link = document.createElement('link');
                                    link.id = id;
                                    link.rel = 'stylesheet';
                                    link.href = candidates[index];
                                    link.onload = () => resolve();
                                    link.onerror = () => {
                                        link.remove();
                                        tryIndex(index + 1);
                                    };
                                    document.head.appendChild(link);
                                };

                                tryIndex(0);
                            });
                        },
                        loadScript(candidates, id) {
                            const existing = document.getElementById(id);
                            if (existing) {
                                return existing.dataset.loaded === 'true'
                                    ? Promise.resolve()
                                    : new Promise((resolve, reject) => {
                                        existing.addEventListener('load', () => resolve(), { once: true });
                                        existing.addEventListener('error', () => reject(new Error('Unable to load script.')), { once: true });
                                    });
                            }

                            return new Promise((resolve, reject) => {
                                const tryIndex = (index) => {
                                    if (index >= candidates.length) {
                                        reject(new Error('Unable to load script.'));
                                        return;
                                    }

                                    const script = document.createElement('script');
                                    script.id = id;
                                    script.src = candidates[index];
                                    script.defer = true;
                                    script.dataset.loaded = 'false';
                                    script.onload = () => {
                                        script.dataset.loaded = 'true';
                                        resolve();
                                    };
                                    script.onerror = () => {
                                        script.remove();
                                        tryIndex(index + 1);
                                    };
                                    document.head.appendChild(script);
                                };

                                tryIndex(0);
                            });
                        },
                        initMap() {
                            const el = this.$refs.map;

                            if (!el || el.dataset.mapInitialized === 'true' || !window.L) {
                                return;
                            }

                            el.dataset.mapInitialized = 'true';

                            const mapInstance = L.map(el, {
                                center: this.mapCenter,
                                zoom: 11,
                                scrollWheelZoom: false,
                            });

                            el._leafletMap = mapInstance;

                            const isDark = document.documentElement.classList.contains('dark');
                            const tileUrl = isDark
                                ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
                                : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';

                            L.tileLayer(tileUrl, {
                                attribution: '&copy; OpenStreetMap &copy; CARTO',
                                subdomains: 'abcd',
                                maxZoom: 20,
                            }).addTo(mapInstance);

                            if (this.heatmapData.length > 0) {
                                L.heatLayer(this.heatmapData, {
                                    radius: 34,
                                    blur: 22,
                                    maxZoom: 16,
                                    gradient: {
                                        0.2: '#60a5fa',
                                        0.45: '#facc15',
                                        0.7: '#fb923c',
                                        1.0: '#ef4444',
                                    },
                                }).addTo(mapInstance);
                            }

                            const bounds = [];

                            this.hotspots.forEach((hotspot) => {
                                bounds.push([hotspot.latitude, hotspot.longitude]);

                                const isTopRank = hotspot.rank === 1;
                                const hasActiveIssue = hotspot.aduan_aktif > 0;
                                const baseColor = isTopRank ? '#dc2626' : (hasActiveIssue ? '#f97316' : '#2563eb');
                                const radius = Math.min(28, 10 + hotspot.total_laporan * 2);

                                const icon = L.divIcon({
                                    className: 'hotspot-div-icon',
                                    html: '<div style=\'position: relative; width: ' + radius + 'px; height: ' + radius + 'px;\'><span style=\'position: absolute; inset: 0; border-radius: 9999px; background: ' + baseColor + '; opacity: 0.28; animation: hotspot-ping 1.8s cubic-bezier(0, 0, 0.2, 1) infinite;\'></span><span style=\'position: absolute; inset: 4px; border-radius: 9999px; background: ' + baseColor + '; border: 2px solid #fff; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.25);\'></span></div>',
                                    iconSize: [radius, radius],
                                    iconAnchor: [radius / 2, radius / 2],
                                });

                                const marker = L.marker([hotspot.latitude, hotspot.longitude], { icon }).addTo(mapInstance);

                                const badgeText = hotspot.rank ? 'Peringkat #' + hotspot.rank : 'Perangkat Terpetakan';
                                const popupContent = '<div style=\'min-width: 220px; color: #0f172a; font-family: ui-sans-serif, system-ui, sans-serif;\'>'
                                    + '<div style=\'display: inline-flex; margin-bottom: 8px; border-radius: 9999px; background: #fee2e2; padding: 3px 8px; font-size: 10px; font-weight: 800; color: #b91c1c;\'>' + badgeText + '</div>'
                                    + '<div style=\'font-size: 14px; font-weight: 800; margin-bottom: 4px;\'>' + hotspot.nama_perangkat + '</div>'
                                    + '<div style=\'font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 8px;\'>' + hotspot.jenis_perangkat + ' · ' + (hotspot.wilayah || '-') + '</div>'
                                    + '<div style=\'font-size: 12px; color: #475569; margin-bottom: 10px;\'>' + hotspot.lokasi + '</div>'
                                    + '<div style=\'display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px;\'>'
                                    + '<div style=\'border-radius: 12px; background: #eff6ff; padding: 8px;\'><div style=\'font-size: 10px; color: #1d4ed8; font-weight: 700; text-transform: uppercase;\'>Total Laporan</div><div style=\'margin-top: 4px; font-size: 18px; font-weight: 800; color: #0f172a;\'>' + hotspot.total_laporan + '</div></div>'
                                    + '<div style=\'border-radius: 12px; background: #fff7ed; padding: 8px;\'><div style=\'font-size: 10px; color: #c2410c; font-weight: 700; text-transform: uppercase;\'>Aduan Aktif</div><div style=\'margin-top: 4px; font-size: 18px; font-weight: 800; color: #0f172a;\'>' + hotspot.aduan_aktif + '</div></div>'
                                    + '</div>'
                                    + '<div style=\'margin-top: 10px; font-size: 11px; color: #64748b;\'>Laporan terakhir: ' + (hotspot.laporan_terakhir || '-') + '</div>'
                                    + '</div>';

                                marker.bindPopup(popupContent);
                            });

                            if (bounds.length > 0) {
                                mapInstance.fitBounds(bounds, { padding: [30, 30] });
                            }

                            setTimeout(() => mapInstance.invalidateSize(), 250);
                        },
                    }"
                    x-init="init()"
                >
                    @unless ($hasHotspotData)
                        <div class="mb-4 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-xs text-sky-700 dark:border-sky-500/20 dark:bg-sky-500/10 dark:text-sky-200">
                            Belum ada data laporan gangguan di database, jadi peta sementara menampilkan titik perangkat terdaftar. Begitu laporan masuk, widget ini otomatis berubah menjadi peta hotspot gangguan.
                        </div>
                    @endunless

                    <div style="display: flex; flex-wrap: wrap; gap: 16px; width: 100%;">
                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 shadow-inner dark:border-white/10 dark:bg-slate-900" style="flex: 2 1 600px; min-height: 520px;">
                            <div x-ref="map" wire:ignore style="height: 520px; width: 100%;"></div>

                            <div
                                x-show="loadError"
                                x-cloak
                                class="border-t border-red-100 bg-red-50 px-4 py-3 text-xs text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-200"
                            >
                                Aset peta gagal dimuat. Periksa folder `public/leaflet` atau koneksi ke CDN fallback.
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-slate-900" style="flex: 1 1 300px;">
                            <div class="mb-3 flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                                        {{ $hasHotspotData ? 'Hotspot Tertinggi' : 'Perangkat Terpetakan' }}
                                    </h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $hasHotspotData ? 'Urut berdasarkan jumlah laporan gangguan.' : 'Menampilkan perangkat yang sudah memiliki koordinat.' }}
                                    </p>
                                </div>
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600 dark:bg-white/10 dark:text-slate-300">
                                    {{ $hasHotspotData ? $hotspotCount : $mappedDeviceCount }} titik
                                </span>
                            </div>

                            <div class="space-y-3">
                                @foreach (collect($hotspots)->take(5) as $hotspot)
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-white/10 dark:bg-white/5">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                    {{ $hotspot['rank'] ? '#'.$hotspot['rank'].' ' : '' }}{{ $hotspot['nama_perangkat'] }}
                                                </div>
                                                <div class="mt-1 text-[11px] uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                                    {{ $hotspot['jenis_perangkat'] }} • {{ $hotspot['wilayah'] ?: '-' }}
                                                </div>
                                                <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                                    {{ $hotspot['lokasi'] }}
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <div class="text-lg font-black text-slate-900 dark:text-white">{{ $hotspot['total_laporan'] }}</div>
                                                <div class="text-[11px] text-slate-500 dark:text-slate-400">laporan</div>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex items-center justify-between text-xs">
                                            <span class="rounded-full bg-amber-100 px-2.5 py-1 font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                                                {{ $hasHotspotData ? 'Aktif: '.$hotspot['aduan_aktif'] : 'Koordinat siap dipakai' }}
                                            </span>
                                            <span class="text-slate-400 dark:text-slate-500">
                                                {{ $hotspot['laporan_terakhir'] ?: ($hasHotspotData ? 'Belum ada waktu laporan' : 'Belum ada riwayat laporan') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
