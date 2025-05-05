day là project test khong

cài đặt composer để có thể xuất báo cáo bằng pdf:
===> " composer require mpdf/mpdf "



=======> Chức năng chưa phát triển <==========

        <!-- Phần đánh giá sản phẩm -->
        <?php if (!empty($reviews)): ?>
            <div class="product-reviews">
                <h2>Đánh giá từ khách hàng</h2>
                <div class="reviews-container">
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <img src="<?php echo $review['anhDaiDien'] ?: 'app/assets/images/default.png'; ?>"
                                        alt="Avatar" class="reviewer-avatar">
                                    <span class="reviewer-name"><?php echo $review['hoTen']; ?></span>
                                </div>
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i
                                            class="material-icons <?php echo ($i <= $review['soSao']) ? 'star-filled' : 'star-empty'; ?>">
                                            <?php echo ($i <= $review['soSao']) ? 'star' : 'star_border'; ?>
                                        </i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="review-content">
                                <p><?php echo $review['nhanXet']; ?></p>
                            </div>
                            <div class="review-date">
                                <?php echo date('d/m/Y', strtotime($review['ngayTao'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Sản phẩm liên quan -->
        <?php if (!empty($relatedProducts)): ?>
            <div class="related-products">
                <h2>Sản phẩm liên quan</h2>
                <div class="products-grid" id="productsGrid">
                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                        <div class="product-card">
                            <div class="image-container">
                                <?php if (!empty($relatedProduct['badge_name'])): ?>
                                    <span class="badges"
                                        style="background-color: <?= $relatedProduct['badgeColor'] ?? '#20B2AA'; ?>;">
                                        <?= htmlspecialchars($relatedProduct['badge_name']); ?>
                                    </span>
                                <?php endif; ?>
                                <img src="<?= htmlspecialchars($relatedProduct['images']['main']); ?>"
                                    alt="<?= htmlspecialchars($relatedProduct['tenSanPham']); ?>" />
                                <div class="overlay-buttons">
                                    <!-- Nút Thêm vào giỏ hàng -->
                                    <button aria-label="Thêm vào giỏ hàng"
                                        onclick="handleAddToCart(<?= $relatedProduct['sanPham_id']; ?>, <?= $relatedProduct['gia']; ?>)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="9" cy="21" r="1"></circle>
                                            <circle cx="20" cy="21" r="1"></circle>
                                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61l1.38-7.39H6">
                                            </path>
                                        </svg>
                                    </button>
                                    <!-- Nút Toggle wishlist -->
                                    <button aria-label="Toggle yêu thích"
                                        onclick="handleToggleWishlist(<?= $relatedProduct['sanPham_id']; ?>, this)"
                                        class="<?= isset($relatedProduct['in_wishlist']) && $relatedProduct['in_wishlist'] ? 'wishlist-active' : '' ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path
                                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                            </path>
                                        </svg>
                                        <span class="tooltip">Bỏ yêu thích</span>
                                    </button>
                                    <!-- Nút Xem chi tiết -->
                                    <a href="/DA_ADMIN_TEST/san-pham/chi-tiet/<?= $relatedProduct['sanPham_id']; ?>"
                                        aria-label="Xem chi tiết">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4.5-7 11-7 11 7 11 7-4.5 7-11 7-11-7-11-7z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3><?= htmlspecialchars($relatedProduct['tenSanPham']); ?></h3>
                                <h4><?= htmlspecialchars($relatedProduct['category_name']); ?></h4>
                                <div class="price">
                                    <?= number_format($relatedProduct['gia'], 0, ',', '.'); ?> đ
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-products-message">
                        Hiện không có sản phẩm liên quan nào.
                    </div>
                <?php endif; ?>
            </div>
