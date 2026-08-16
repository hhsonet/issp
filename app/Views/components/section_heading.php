<?php /** @var string $eyebrow */ /** @var string $title */ /** @var string $description */ ?>
<div class="section-heading">
    <?php if (! empty($eyebrow)): ?><div class="eyebrow"><?= esc($eyebrow) ?></div><?php endif; ?>
    <h2><?= esc($title ?? '') ?></h2>
    <?php if (! empty($description)): ?><p><?= esc($description) ?></p><?php endif; ?>
</div>
