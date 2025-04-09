@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        Heatmap v2
    </div>
    <div class="card-body">
        <div class="col-3">
            <div class="form-group">
                <label for="rangeSelect" class="form-label">Filtrer par plage :</label>
                <select id="rangeSelect" name="rangeSelect" class="form-select select2">
                    <option value="">Toutes les plages privées</option>
                    <option value="10">10.0.0.0/8</option>
                    <option value="172">172.16.0.0/12</option>
                    <option value="192">192.168.0.0/16</option>
                </select>
            </div>
        </div>
        <canvas id="ipv4Heatmap" width="800" height="800" style="background: white; border: 1px solid #ccc;"></canvas>
        <div class="mt-3">
          <label>Densité :</label>
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 0.8rem;">0</span>
            <div style="flex: 1; height: 20px; background: linear-gradient(to right, rgba(0,123,255,0.1), rgba(0,123,255,1)); border: 1px solid #ccc;"></div>
            <span style="font-size: 0.8rem;">5+</span>
          </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix@1.3.0/dist/chartjs-chart-matrix.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const canvas = document.getElementById('ipv4Heatmap');
  const ctx = canvas.getContext('2d');

  // --- Fonctions Hilbert (ordre 8) ---
  function rot(n, x, y, rx, ry) {
    if (ry === 0) {
      if (rx === 1) {
        x = n - 1 - x;
        y = n - 1 - y;
      }
      return [y, x];
    }
    return [x, y];
  }

  function hilbertIndexToXY(index, order) {
    let x = 0, y = 0;
    for (let s = 1; s < (1 << order); s <<= 1) {
      const rx = 1 & (index >> 1);
      const ry = 1 & (index ^ rx);
      const [nx, ny] = rot(s, x, y, rx, ry);
      x = nx + s * rx;
      y = ny + s * ry;
      index >>= 2;
    }
    return { x, y };
  }

  function ipToIndex(ip) {
    const parts = ip.split('.').map(Number);
    return (parts[1] << 8) + parts[2]; // 10.X.Y.Z
  }

  // --- IP de test dans 10.0.0.0/16 ---
  const ipList = [
    '10.0.0.1', '10.0.0.2',
    '10.1.1.1',
    '10.2.0.1', '10.2.0.2', '10.2.0.3',
    '10.10.10.10', '10.10.10.11', '10.10.10.12', '10.10.10.13',
    '10.20.20.1', '10.20.21.1',
    '10.100.100.100'
  ];

  // Regrouper les occurrences
  const counts = {};
  ipList.forEach(ip => {
    const idx = ipToIndex(ip);
    counts[idx] = (counts[idx] || 0) + 1;
  });

  const order = 8;
  const points = Object.entries(counts).map(([idx, v]) => {
    const { x, y } = hilbertIndexToXY(parseInt(idx), order);
    return { x, y, v: v };
  });

backgroundColor(ctx) {
  const value = ctx.raw?.v ?? 0;
  const alpha = Math.min(1, value / 5); // ← adapte ici
  return `rgba(0, 123, 255, ${alpha})`;
}
  const chart = new Chart(ctx, {
    type: 'matrix',
    data: {
      datasets: [{
        label: 'IPv4 Hilbert Heatmap',
        data: points,
        width: () => 3,
        height: () => 3,
        backgroundColor(ctx) {
          const value = ctx.raw?.v ?? 0;
          const alpha = Math.min(1, value / 5);
          return `rgba(0, 123, 255, ${alpha})`;
        },
        borderWidth: 0
      }]
    },
    options: {
      responsive: false,
      animation: {
        duration: 800,
        easing: 'easeOutQuart'
      },
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            title: () => '',
            label(ctx) {
              const { x, y, v } = ctx.raw;
              return `Bloc (${x}, ${y}) : ${v} IP`;
            }
          }
        }
      },
      layout: { padding: 0 },

      scales: {
        x: {
          type: 'linear',
          min: 0,
          max: 256,
          display: false
        },
        y: {
          type: 'linear',
          min: 0,
          max: 256,
          reverse: true,
          display: false
        }
      }
    }
  });
});
</script>
@endsection
