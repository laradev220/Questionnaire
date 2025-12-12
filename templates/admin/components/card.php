<?php
// Reusable card component
// Usage: include 'card.php'; then use the HTML below with variables
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 <?php echo isset($cardClass) ? $cardClass : 'p-6'; ?>">
    <?php if (isset($cardTitle)): ?>
        <h3 class="text-xl font-semibold text-gray-900 mb-4"><?php echo $cardTitle; ?></h3>
    <?php endif; ?>
    <?php echo isset($cardContent) ? $cardContent : ''; ?>
</div>