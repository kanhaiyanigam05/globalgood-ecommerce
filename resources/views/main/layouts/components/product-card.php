<?php
function renderProductCard($product)
{
    // Convert price to float
    $price = isset($product['price'])
        ? floatval(str_replace(',', '', $product['price']))
        : 0;

    // Discount percentage
    $discountPercent = intval($product['discountPercent'] ?? 0);


    // Calculate discounted price
    $discountedPrice = ($discountPercent > 0)
        ? $price - ($price * $discountPercent / 100)
        : $price;

    $isSoldOut = !empty($product['soldOut']);
?>
    <div class="product-card">
        <div class="product-top-wrapper">

            <a href="###" class="product-image-wrapper <?= $isSoldOut ? 'grayscale' : '' ?>">
                <img class="product-image-front" src="<?= $product['front'] ?>" alt="" draggable="false" />
                <img class="product-image-back" src="<?= $product['back'] ?>" alt="" draggable="false" />
            </a>

            <div class="product-badge-wrapper-right <?= empty($product['bestseller']) ? 'mt-3' : '' ?> <?= $isSoldOut ? 'grayscale' : '' ?>">
                <?php if (!empty($product['bestseller'])): ?>
                    <div class="product-badge bg-lime-600">
                        <p class="font-semibold tracking-wide">Bestseller</p>
                    </div>
                <?php endif; ?>

                <button class="product-badge-like-btn" type="button">
                    <i class="iconify" data-icon="solar:heart-linear"></i>
                </button>
            </div>

            <?php if (!$isSoldOut && $discountPercent > 0): ?>
                <div class="product-badge-wrapper-left">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 442.71 496.08">
                        <polygon fill="#410d0f" points="0 0 442.71 0 442.71 495.88 229.56 407.1 0 496.08 0 0" />
                        <text fill="#fff" font-size="200" x="70" y="280"><?= $discountPercent ?></text>
                        <text fill="#fff" font-size="120" x="300" y="240">%</text>
                        <text fill="#fff" font-size="60" x="295" y="325">OFF</text>
                    </svg>
                </div>
            <?php endif; ?>

            <?php if (!$isSoldOut): ?>
                <div class="product-quick-view-wrapper">
                    <button onclick="openModal(this);" data-model-id="product-quick-view"
                        class="product-quick-view-btn" type="button">
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
                <p class="product-card-title">
                    <?= htmlspecialchars($product['name'] ?? 'Product Name') ?>
                </p>

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