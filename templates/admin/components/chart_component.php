<?php
// Reusable chart component using Chart.js
// Set variables: $chartId, $chartType='bar', $chartData=[], $chartOptions=[], $chartWidth='400', $chartHeight='200'
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <?php if (isset($chartTitle)): ?>
        <h3 class="text-xl font-semibold text-gray-900 mb-4"><?php echo $chartTitle; ?></h3>
    <?php endif; ?>
    <canvas id="<?php echo isset($chartId) ? $chartId : 'chart'; ?>" width="<?php echo isset($chartWidth) ? $chartWidth : '400'; ?>" height="<?php echo isset($chartHeight) ? $chartHeight : '200'; ?>"></canvas>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('<?php echo isset($chartId) ? $chartId : 'chart'; ?>').getContext('2d');
    const chart = new Chart(ctx, {
        type: '<?php echo isset($chartType) ? $chartType : 'bar'; ?>',
        data: <?php echo isset($chartData) ? json_encode($chartData) : '{}'; ?>,
        options: <?php echo isset($chartOptions) ? json_encode($chartOptions) : '{}'; ?>
    });
});
</script>