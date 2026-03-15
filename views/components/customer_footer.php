<?php if ($success = Message::get('success')): ?>
  <!-- success toast same as admin -->
<?php endif; ?>

<!-- Customer Footer -->
<footer class="bg-[#333] text-gray-300 mt-12">
  <div class="max-w-[1200px] mx-auto px-4 py-12">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

      <!-- Brand -->
      <div>
        <div class="flex items-center gap-2 mb-4">
          <div class="w-8 h-8 bg-[#4CAF50] rounded-lg flex items-center justify-center">
            <i data-lucide="book-open" class="w-[18px] h-[18px] text-white"></i>
          </div>
          <span class="text-white text-lg font-bold">Book<span class="text-[#4CAF50]">Store</span></span>
        </div>
        <p class="text-sm leading-relaxed mb-4">Cửa hàng sách trực tuyến uy tín, cung cấp hàng nghìn đầu sách chất lượng với giá tốt nhất.</p>
        <div class="flex gap-3">
          <a href="#" class="w-8 h-8 bg-gray-600 hover:bg-[#4CAF50] rounded-lg flex items-center justify-center transition-colors">
            <i data-lucide="facebook" class="w-[14px] h-[14px]"></i>
          </a>
          <a href="#" class="w-8 h-8 bg-gray-600 hover:bg-[#4CAF50] rounded-lg flex items-center justify-center transition-colors">
            <i data-lucide="instagram" class="w-[14px] h-[14px]"></i>
          </a>
          <a href="#" class="w-8 h-8 bg-gray-600 hover:bg-[#4CAF50] rounded-lg flex items-center justify-center transition-colors">
            <i data-lucide="youtube" class="w-[14px] h-[14px]"></i>
          </a>
        </div>
      </div>

      <!-- Danh mục sách -->
      <div>
        <h4 class="text-white font-semibold mb-4">Danh mục sách</h4>
        <ul class="space-y-2 text-sm">
          <?php
          $footerCategories = [
            ['name' => 'Văn học', 'slug' => 'van-hoc'],
            ['name' => 'Kinh tế', 'slug' => 'kinh-te'],
            ['name' => 'Thiếu nhi', 'slug' => 'thieu-nhi'],
            ['name' => 'Kỹ năng sống', 'slug' => 'ky-nang-song'],
            ['name' => 'Khoa học', 'slug' => 'khoa-hoc'],
            ['name' => 'Lịch sử', 'slug' => 'lich-su'],
          ];
          foreach ($footerCategories as $cat):
          ?>
            <li>
              <a href="<?= BASE_URL ?>?act=books&category=<?= urlencode($cat['slug']) ?>"
                 class="hover:text-[#4CAF50] transition-colors"><?= $cat['name'] ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Hỗ trợ -->
      <div>
        <h4 class="text-white font-semibold mb-4">Hỗ trợ</h4>
        <ul class="space-y-2 text-sm">
          <?php foreach (['Hướng dẫn mua hàng','Chính sách đổi trả','Chính sách vận chuyển','Câu hỏi thường gặp','Liên hệ chúng tôi'] as $item): ?>
            <li><a href="#" class="hover:text-[#4CAF50] transition-colors"><?= $item ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Liên hệ -->
      <div>
        <h4 class="text-white font-semibold mb-4">Liên hệ</h4>
        <ul class="space-y-3 text-sm">
          <li class="flex items-start gap-2">
            <i data-lucide="map-pin" class="w-[14px] h-[14px] text-[#4CAF50] mt-0.5 shrink-0"></i>
            <span>123 Nguyễn Huệ, Q.1, TP.HCM</span>
          </li>
          <li class="flex items-center gap-2">
            <i data-lucide="phone" class="w-[14px] h-[14px] text-[#4CAF50] shrink-0"></i>
            <span>1800 1234</span>
          </li>
          <li class="flex items-center gap-2">
            <i data-lucide="mail" class="w-[14px] h-[14px] text-[#4CAF50] shrink-0"></i>
            <span>support@bookstore.vn</span>
          </li>
        </ul>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="border-t border-gray-600 mt-8 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
      <p>© <?= date('Y') ?> BookStore. Tất cả quyền được bảo lưu.</p>
      <div class="flex gap-4">
        <a href="#" class="hover:text-[#4CAF50] transition-colors">Điều khoản dịch vụ</a>
        <a href="#" class="hover:text-[#4CAF50] transition-colors">Chính sách bảo mật</a>
      </div>
    </div>
  </div>
</footer>

</body>
<script>lucide.createIcons();</script>
</html>
