<?php
// Reusable button component
// Set variables before including: $buttonText, $buttonType='button', $buttonClass='bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700', $buttonIcon='', $buttonHref=''
if (isset($buttonHref) && $buttonHref): ?>
    <a href="<?php echo $buttonHref; ?>" class="<?php echo isset($buttonClass) ? $buttonClass : 'inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200'; ?>">
        <?php if (isset($buttonIcon) && $buttonIcon): ?><i class="<?php echo $buttonIcon; ?> mr-2"></i><?php endif; ?>
        <?php echo isset($buttonText) ? $buttonText : 'Button'; ?>
    </a>
<?php else: ?>
    <button type="<?php echo isset($buttonType) ? $buttonType : 'button'; ?>"
            class="<?php echo isset($buttonClass) ? $buttonClass : 'inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200'; ?>">
        <?php if (isset($buttonIcon) && $buttonIcon): ?><i class="<?php echo $buttonIcon; ?> mr-2"></i><?php endif; ?>
        <?php echo isset($buttonText) ? $buttonText : 'Button'; ?>
    </button>
<?php endif; ?>