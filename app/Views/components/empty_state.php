<?php
/** @var string $title */
/** @var string $message */
/** @var string $actionUrl */
/** @var string $actionLabel */
?>
<div class="empty-state card">
    <div class="empty-state__icon" aria-hidden="true">⌁</div>
    <h3><?= esc($title) ?></h3>
    <p><?= esc($message) ?></p>
    <?php if (! empty($actionUrl) && ! empty($actionLabel)): ?>
        <a class="btn btn-primary" href="<?= esc($actionUrl) ?>"><?= esc($actionLabel) ?></a>
    <?php endif; ?>
</div>
