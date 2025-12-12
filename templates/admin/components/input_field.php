<?php
// Reusable input field component
// Set variables: $inputType='text', $inputName, $inputId, $inputLabel, $inputPlaceholder='', $inputRequired=false, $inputValue='', $inputClass='w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200'
?>
<div class="<?php echo isset($inputWrapperClass) ? $inputWrapperClass : ''; ?>">
    <?php if (isset($inputLabel)): ?>
        <label for="<?php echo isset($inputId) ? $inputId : $inputName; ?>" class="block text-sm font-medium text-gray-700 mb-2">
            <?php echo $inputLabel; ?>
            <?php if (isset($inputRequired) && $inputRequired): ?><span class="text-red-500">*</span><?php endif; ?>
        </label>
    <?php endif; ?>
    <input type="<?php echo isset($inputType) ? $inputType : 'text'; ?>"
           name="<?php echo $inputName; ?>"
           id="<?php echo isset($inputId) ? $inputId : $inputName; ?>"
           <?php if (isset($inputRequired) && $inputRequired): ?>required<?php endif; ?>
           value="<?php echo isset($inputValue) ? htmlspecialchars($inputValue) : ''; ?>"
           placeholder="<?php echo isset($inputPlaceholder) ? $inputPlaceholder : ''; ?>"
           class="<?php echo isset($inputClass) ? $inputClass : 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200'; ?>">
</div>