<?php
// ======================== THUẬT TOÁN DIJKSTRA ========================
class Dijkstra {
    private $graph;

    public function __construct(array $graph) {
        $this->graph = $graph;
    }

    public function findShortestPath(string $start, string $end): array {
        $dist = [];
        $prev = [];
        $visited = [];

        // Khởi tạo
        foreach (array_keys($this->graph) as $v) {
            $dist[$v] = INF;
            $prev[$v] = null;
            $visited[$v] = false;
        }
        $dist[$start] = 0;

        $queue = new SplPriorityQueue();
        $queue->insert($start, 0);

        while (!$queue->isEmpty()) {
            $current = $queue->extract();

            if ($visited[$current]) continue;
            $visited[$current] = true;

            if (!isset($this->graph[$current])) continue;

            foreach ($this->graph[$current] as $neighbor => $weight) {
                if ($visited[$neighbor]) continue;

                $alt = $dist[$current] + $weight;

                if ($alt < $dist[$neighbor]) {
                    $dist[$neighbor] = $alt;
                    $prev[$neighbor] = $current;
                    $queue->insert($neighbor, $alt);
                }
            }
        }

        // Tái tạo đường đi
        $path = [];
        $current = $end;
        while ($current !== null) {
            array_unshift($path, $current);
            $current = $prev[$current] ?? null;
        }

        if (empty($path) || $path[0] !== $start) {
            return [null, INF];
        }

        return [$path, $dist[$end]];
    }

    public static function formatPath(array $path): string {
        return empty($path) ? "Không tìm thấy đường đi" : implode(" → ", $path);
    }
}

// ======================== DỮ LIỆU ĐỒ THỊ MẪU ========================
$graph = [
    'A' => ['B' => 4, 'C' => 2],
    'B' => ['A' => 4, 'C' => 5, 'D' => 10],
    'C' => ['A' => 2, 'B' => 5, 'D' => 3, 'E' => 11],
    'D' => ['B' => 10, 'C' => 3, 'E' => 2, 'F' => 6],
    'E' => ['C' => 11, 'D' => 2, 'F' => 5],
    'F' => ['D' => 6, 'E' => 5]
];

$nodes = array_keys($graph);
sort($nodes);

// ======================== XỬ LÝ FORM ========================
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start = trim($_POST['start'] ?? '');
    $end   = trim($_POST['end']   ?? '');

    if ($start === '' || $end === '') {
        $error = "Vui lòng chọn cả điểm đi và điểm đến!";
    } elseif (!isset($graph[$start]) || !isset($graph[$end])) {
        $error = "Điểm không tồn tại trong bản đồ!";
    } else {
        $dijkstra = new Dijkstra($graph);
        [$path, $distance] = $dijkstra->findShortestPath($start, $end);

        $result = [
            'start'    => $start,
            'end'      => $end,
            'path'     => $path ? Dijkstra::formatPath($path) : 'Không tìm thấy đường đi',
            'distance' => $distance === INF ? '∞' : number_format($distance, 1) . ' km',
            'success'  => $path !== null
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tìm Đường Đi Ngắn Nhất - Dijkstra</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    :root {
      --primary: #4361ee;
      --primary-dark: #3f37c9;
      --light: #f8f9fa;
      --dark: #212529;
      --success: #4cc9f0;
      --danger: #f72585;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', system-ui, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: var(--dark);
      min-height: 100vh;
      padding: 2rem 1rem;
    }

    .container {
      max-width: 900px;
      margin: 0 auto;
      background: white;
      border-radius: 16px;
      box-shadow: 0 15px 40px rgba(0,0,0,0.25);
      overflow: hidden;
    }

    .header {
      background: var(--primary);
      color: white;
      padding: 2.5rem 2rem;
      text-align: center;
    }

    .header h1 {
      font-size: 2.1rem;
      margin-bottom: 0.5rem;
    }

    .content {
      padding: 2.5rem 2.5rem 3rem;
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr auto;
      gap: 1.5rem;
      margin: 2rem 0;
    }

    .form-group {
      position: relative;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.6rem;
      font-weight: 600;
      color: #444;
    }

    select {
      width: 100%;
      padding: 1rem 1.2rem;
      border: 2px solid #ddd;
      border-radius: 10px;
      font-size: 1.1rem;
      background: white;
      transition: all 0.25s;
    }

    select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(67,97,238,0.2);
    }

    .btn {
      padding: 1rem 2.2rem;
      font-size: 1.1rem;
      font-weight: 600;
      color: white;
      background: var(--primary);
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s;
      align-self: flex-end;
    }

    .btn:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(67,97,238,0.3);
    }

    .result {
      margin-top: 2.5rem;
      padding: 2rem;
      border-radius: 12px;
      background: #f0f4ff;
      border-left: 5px solid var(--primary);
      animation: fadeIn 0.6s ease-out;
    }

    .result-title {
      font-size: 1.4rem;
      margin-bottom: 1.2rem;
      color: var(--primary-dark);
    }

    .path-display {
      font-size: 1.8rem;
      font-weight: 700;
      color: #1a1a1a;
      margin: 1.2rem 0;
      padding: 1rem;
      background: rgba(255,255,255,0.7);
      border-radius: 10px;
      text-align: center;
    }

    .distance {
      font-size: 1.4rem;
      color: #333;
    }

    .distance strong {
      color: var(--primary-dark);
      font-size: 1.6rem;
    }

    .error {
      margin-top: 1.5rem;
      padding: 1.2rem;
      background: #ffebee;
      color: #c62828;
      border-radius: 10px;
      border-left: 5px solid #c62828;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
      .btn {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="header">
    <h1>Tìm Đường Đi Ngắn Nhất</h1>
    <p style="opacity:0.9;">Thuật toán Dijkstra • Bản đồ mẫu 6 điểm</p>
  </div>

  <div class="content">
    <form method="POST" class="form-grid">
      <div class="form-group">
        <label>Điểm xuất phát</label>
        <select name="start" required>
          <option value="">Chọn điểm đi...</option>
          <?php foreach($nodes as $node): ?>
            <option value="<?= $node ?>" <?= ($result['start']??'') === $node ? 'selected' : '' ?>>
              <?= $node ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Điểm đến</label>
        <select name="end" required>
          <option value="">Chọn điểm đến...</option>
          <?php foreach($nodes as $node): ?>
            <option value="<?= $node ?>" <?= ($result['end']??'') === $node ? 'selected' : '' ?>>
              <?= $node ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="submit" class="btn">
        <i class="fas fa-route"></i> Tìm đường
      </button>
    </form>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($result): ?>
    <div class="result">
      <div class="result-title">
        Kết quả từ <strong><?= $result['start'] ?></strong> → <strong><?= $result['end'] ?></strong>
      </div>

      <div class="path-display">
        <?= htmlspecialchars($result['path']) ?>
      </div>

      <div class="distance" style="text-align:center">
        Khoảng cách: <strong><?= $result['distance'] ?></strong>
        <?php if ($result['success']): ?>
          <span style="color:#555; font-size:0.95rem;">(đường đi ngắn nhất)</span>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <div style="margin-top:2.5rem; text-align:center; color:#666; font-size:0.95rem;">
      Đồ thị mẫu gồm 6 điểm (A → F) • Bạn có thể dễ dàng thay đổi mảng \$graph trong code
    </div>
  </div>
</div>

</body>
</html>
