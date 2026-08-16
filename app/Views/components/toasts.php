<?php
$toasts = [];
foreach (['success', 'error', 'warning', 'info'] as $type) {
    $message = session()->getFlashdata($type);
    if ($message) {
        $toasts[] = ['type' => $type, 'message' => $message];
    }
}
?>
<div class="toast-container" aria-live="polite" aria-atomic="true">
    <?php foreach ($toasts as $toast): ?>
        <div class="toast toast-<?= esc($toast['type']) ?>" data-toast>
            <div class="toast__message"><?= esc($toast['message']) ?></div>
            <button type="button" class="toast__close" aria-label="Close notification" data-toast-close>&times;</button>
        </div>
    <?php endforeach; ?>
</div>
