<div class="space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-900">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">Ringkasan data laporan gangguan perangkat.</p>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 xl:grid-cols-8">
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $total }}</p>
        </div>
        <div class="rounded-xl border-l-4 border-l-red-400 border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-red-500">Open</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $open }}</p>
        </div>
        <div class="rounded-xl border-l-4 border-l-indigo-400 border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">Diterima</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $diterima }}</p>
        </div>
        <div class="rounded-xl border-l-4 border-l-amber-400 border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">Proses</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $proses }}</p>
        </div>
        <div class="rounded-xl border-l-4 border-l-orange-400 border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-orange-500">Menunggu</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $menunggu }}</p>
        </div>
        <div class="rounded-xl border-l-4 border-l-yellow-400 border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-yellow-600">Selesai</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $selesai }}</p>
        </div>
        <div class="rounded-xl border-l-4 border-l-emerald-400 border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Diverifikasi</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $diverifikasi }}</p>
        </div>
        <div class="rounded-xl border-l-4 border-l-slate-400 border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Ditolak</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $ditolak }}</p>
        </div>
    </div>

    <!-- Leaflet.js Peta Sebaran Laporan & Heatmap -->
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Peta Sebaran Laporan & Kepadatan Gangguan</h3>
                <p class="text-xs text-slate-400 mt-0.5">Menampilkan marker aduan aktif dan grafik area rawan gangguan (heatmap).</p>
            </div>
            <div class="flex items-center space-x-3 text-xs">
                <span class="inline-flex items-center font-medium text-slate-600">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block mr-1"></span> Aduan Aktif
                </span>
                <span class="inline-flex items-center font-medium text-slate-600">
                    <span class="w-3 h-3 rounded-sm bg-gradient-to-r from-blue-500 via-yellow-400 to-red-500 inline-block mr-1 opacity-70"></span> Daerah Rawan (Heatmap)
                </span>
            </div>
        </div>

        <!-- Kontainer Peta -->
        <div id="map" class="rounded-lg border border-slate-100 overflow-hidden shadow-inner bg-slate-50" style="height: 450px; z-index: 1;"></div>
    </div>

    <!-- Aset Leaflet.js CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi Peta Jakarta
            var map = L.map('map', {
                center: [-6.2088, 106.8456],
                zoom: 11,
                scrollWheelZoom: false
            });

            // Tile Layer CartoDB Positron (modern light grey map style)
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap &copy; CartoDB',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            // Data aduan dari backend
            var activeAduan = @json($activeAduan);
            var heatmapData = @json($heatmapData);

            // Render pin aduan aktif
            activeAduan.forEach(function (aduan) {
                var color = '#ef4444'; // default red
                if (aduan.prioritas === 'Sedang') color = '#f59e0b'; // orange
                if (aduan.prioritas === 'Rendah') color = '#3b82f6'; // blue

                // DivIcon kustom berbentuk bulatan pulsasi modern
                var customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 2.5px solid #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.35); position: relative;">
                            <span style="position: absolute; top: -2.5px; left: -2.5px; display: inline-flex; border-radius: 50%; width: 14px; height: 14px; background-color: ${color}; opacity: 0.55; animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                           </div>`,
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                });

                // Style CSS animasi ping tailwind
                if (!document.getElementById('ping-style')) {
                    var style = document.createElement('style');
                    style.id = 'ping-style';
                    style.innerHTML = `
                        @keyframes ping {
                            75%, 100% {
                                transform: scale(2.2);
                                opacity: 0;
                            }
                        }
                    `;
                    document.head.appendChild(style);
                }

                var marker = L.marker([aduan.latitude, aduan.longitude], { icon: customIcon }).addTo(map);
                
                // Konten Popup dengan style modern Tailwind
                var popupContent = `
                    <div style="font-family: ui-sans-serif, system-ui, sans-serif; min-width: 200px; padding: 4px;">
                        <div style="font-weight: 800; color: #1e293b; font-size: 13px; margin-bottom: 2px;">${aduan.kode_tiket}</div>
                        <div style="font-size: 11px; font-weight: 700; color: #6366f1; text-transform: uppercase; margin-bottom: 6px;">${aduan.jenis_perangkat} · ${aduan.nama_perangkat}</div>
                        <div style="font-size: 11px; color: #64748b; display: flex; align-items: center; gap: 4px; margin-bottom: 8px;">
                            <span>📍</span> <span>${aduan.lokasi}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 6px; margin-top: 6px;">
                            <span style="font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 9999px; background-color: #fef2f2; color: #ef4444;">Prio: ${aduan.prioritas}</span>
                            <span style="font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 9999px; background-color: #e0f2fe; color: #0284c7;">Status: ${aduan.status}</span>
                        </div>
                    </div>
                `;
                marker.bindPopup(popupContent);
            });

            // Render Heatmap daerah rawan
            if (heatmapData.length > 0) {
                L.heatLayer(heatmapData, {
                    radius: 30,
                    blur: 15,
                    maxZoom: 16,
                    max: 1.0,
                    gradient: {
                        0.3: '#3b82f6', // blue
                        0.5: '#10b981', // green
                        0.7: '#f59e0b', // yellow/orange
                        1.0: '#ef4444'  // red
                    }
                }).addTo(map);
            }
        });
    </script>

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800">Distribusi per Wilayah</h3>
            <div class="mt-4 space-y-2.5">
                @forelse($laporanPerWilayah as $item)
                    @php($pct = $total > 0 ? round($item->total / $total * 100) : 0)
                    <div>
                        <div class="mb-1 flex items-center justify-between text-xs">
                            <span class="font-medium text-slate-600">{{ $item->wilayah }}</span>
                            <span class="font-bold text-slate-800">{{ $item->total }}</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-indigo-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-slate-400">Belum ada data.</p>
                @endforelse
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800">Distribusi per Jenis Perangkat</h3>
            <div class="mt-4 space-y-2.5">
                @forelse($laporanPerJenis as $item)
                    @php($pct = $total > 0 ? round($item->total / $total * 100) : 0)
                    <div>
                        <div class="mb-1 flex items-center justify-between text-xs">
                            <span class="font-medium text-slate-600">{{ $item->jenis }}</span>
                            <span class="font-bold text-slate-800">{{ $item->total }}</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-violet-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-slate-400">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h3 class="text-sm font-bold text-slate-800">Laporan Masuk Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-xs uppercase tracking-wide text-slate-400">
                        <th class="px-5 py-3 font-semibold">Tanggal</th>
                        <th class="px-5 py-3 font-semibold">Perangkat</th>
                        <th class="px-5 py-3 font-semibold">Prioritas</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold">Teknisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recent as $r)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-5 py-3">
                            <div class="text-slate-700">{{ $r->tanggal->format('d M Y') }}</div>
                            <div class="font-mono text-[11px] text-indigo-500">{{ $r->kode_tiket }}</div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="font-medium text-slate-800">{{ $r->perangkat->nama_perangkat }}</div>
                            <div class="text-xs text-slate-400">{{ $r->perangkat->jenis }} · {{ $r->perangkat->wilayah ?: '-' }}</div>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold @if($r->prioritas == 'Tinggi') bg-red-50 text-red-600 @elseif($r->prioritas == 'Sedang') bg-amber-50 text-amber-600 @else bg-emerald-50 text-emerald-600 @endif">{{ $r->prioritas }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold @if($r->status == 'Diverifikasi') bg-emerald-100 text-emerald-700 @elseif($r->status == 'Selesai') bg-yellow-100 text-yellow-700 @elseif($r->status == 'Proses') bg-sky-100 text-sky-700 @elseif($r->status == 'Diterima') bg-indigo-100 text-indigo-700 @elseif($r->status == 'Menunggu') bg-orange-100 text-orange-700 @elseif($r->status == 'Ditolak') bg-slate-100 text-slate-500 @else bg-red-100 text-red-700 @endif">{{ $r->status }}</span>
                        </td>
                        <td class="px-5 py-3 text-slate-600">{{ $r->teknisi?->name ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-slate-400">Belum ada laporan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
