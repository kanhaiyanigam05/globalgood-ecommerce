<?php
function renderProductCardAlt($product)
{
    // Convert price to float
    $price = isset($product['price'])
        ? floatval(str_replace(',', '', $product['price']))
        : 0;

    // Discount percentage
    $discountPercent = intval($product['discountPercent'] ?? 0);

    // Condition badge
    $conditionBadge = $product['condition_badge'] ?? null;

    // Description (optional)
    $description = $product['description'] ?? null;

    // Calculate discounted price
    $discountedPrice = ($discountPercent > 0)
        ? $price - ($price * $discountPercent / 100)
        : $price;

    $isSoldOut = !empty($product['soldOut']);
?>
    <div class="product-card">
        <div class="product-top-wrapper">

            <a href="###" class="product-image-wrapper <?= $isSoldOut ? 'grayscale' : '' ?>">
                <img
                    class="product-image-front"
                    src="<?= htmlspecialchars($product['front']) ?>"
                    alt="<?= htmlspecialchars($product['name'] ?? '') ?>"
                    draggable="false" />
                <img
                    class="product-image-back"
                    src="<?= htmlspecialchars($product['back']) ?>"
                    alt="<?= htmlspecialchars($product['name'] ?? '') ?>"
                    draggable="false" />
            </a>

            <div class="product-badge-wrapper-right <?= $isSoldOut ? 'grayscale' : '' ?>">
                <button class="product-badge-like-btn mt-3" type="button" aria-label="Add to wishlist">
                    <i class="iconify" data-icon="solar:heart-linear"></i>
                </button>
            </div>

            <?php if (!empty($conditionBadge)): ?>
                <div class="product-badge-wrapper-left <?= htmlspecialchars($conditionBadge) ?>">
                    <?php if ($conditionBadge === 'donated'): ?>
                        <i class="iconify" data-icon="iconoir:donate"></i>
                        <span>Donated</span>
                    <?php elseif ($conditionBadge === 'new'): ?>
                        <i class="type-icon"></i>
                        <span>New</span>
                    <?php elseif ($conditionBadge === 'refurbished'): ?>
                        <i class="type-icon"></i>
                        <span>Refurbished</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!$isSoldOut): ?>
                <div class="product-quick-view-wrapper">
                    <button
                        onclick="openModal(this);"
                        data-model-id="product-quick-view"
                        class="product-quick-view-btn"
                        type="button">
                        Quick View
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($isSoldOut): ?>
                <div class="solid-out-wrapper">
                    <p>Sold Out</p>
                </div>
            <?php endif; ?>

        </div>

        <a href="###">
            <div class="px-1 py-2 grid gap-y-1">

                <h6 class="product-card-title">
                    <?= htmlspecialchars($product['name'] ?? 'Product Name') ?>
                </h6>

                <?php if (!empty($description)): ?>
                    <p class="product-card-description">
                        <?= htmlspecialchars(mb_strimwidth($description, 0, 90, '…')) ?>
                    </p>
                <?php endif; ?>


                <div class="product-meta">
                    <p class="product-meta__vendor">By <span class="product-meta__vendor-name">GreenRoots Collective</span></p>
                    <span>|</span>
                    <span class="product-meta__impact">Supports Local Jobs</span>
                </div>


                <div class="product-rating">
                    <div class="product-rating-stars">
                        <i class="star-icon star-filled"></i>
                        <i class="star-icon star-filled"></i>
                        <i class="star-icon star-filled"></i>
                        <i class="star-icon star-filled"></i>
                        <i class="star-icon star-empty"></i>
                    </div>
                    <p class="product-rating-count">(1K+)</p>
                </div>

                <div class="grid gap-y-[2px]">
                    <div class="flex gap-x-7 items-center flex-wrap">
                        <?php if ($discountPercent > 0): ?>
                            <p class="price">
                                $<span><?= number_format($discountedPrice, 2) ?></span>
                            </p>
                            <p class="price line-through opacity-60">
                                $<span><?= number_format($price, 2) ?></span>
                            </p>
                        <?php else: ?>
                            <p class="price">
                                $<span><?= number_format($price, 2) ?></span>
                            </p>
                        <?php endif; ?>
                    </div>

                    <?php if ($discountPercent > 0): ?>
                        <p class="percent-off"><?= $discountPercent ?>% off</p>
                    <?php endif; ?>
                </div>

            </div>
        </a>
    </div>
<?php
}
?>