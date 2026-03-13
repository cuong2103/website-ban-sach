<?php
// Ensure customer directory exists (created manually if needed)
require_once './views/components/navbar.php';
?>

<!-- Banner Slider -->
<div class="relative overflow-hidden" id="banner-slider">
  <?php
  $banners = [
    ['title' => 'Khai phá tri thức',   'sub' => 'Hàng nghìn đầu sách chất lượng giảm đến 50%', 'cta' => 'Mua ngay',   'from' => '#4CAF50', 'to' => '#1B5E20'],
    ['title' => 'Sách mới tháng 3',    'sub' => 'Cập nhật hàng ngàn đầu sách mới nhất',          'cta' => 'Khám phá',   'from' => '#1565C0', 'to' => '#0D47A1'],
    ['title' => 'Flash Sale mỗi ngày', 'sub' => 'Giảm giá sốc chỉ trong 24 giờ',                'cta' => 'Xem ưu đãi', 'from' => '#E64A19', 'to' => '#BF360C'],
  ];
  foreach ($banners as $i => $b): ?>
    <div class="banner-slide absolute inset-0 h-64 md:h-80 flex flex-col justify-center px-6 md:px-16 transition-opacity duration-500 <?= $i === 0 ? 'opacity-100 relative' : 'opacity-0 pointer-events-none' ?>"
         style="background: linear-gradient(to right, <?= $b['from'] ?>, <?= $b['to'] ?>);">
      <h1 class="text-3xl md:text-4xl text-white font-bold mb-3"><?= $b['title'] ?></h1>
      <p class="text-white/80 text-lg mb-6"><?= $b['sub'] ?></p>
      <a href="<?= BASE_URL ?>?act=books" class="inline-block bg-[#FFC107] hover:bg-[#FFB300] text-[#333] px-6 py-2.5 rounded-lg w-fit font-semibold transition-colors">
        <?= $b['cta'] ?>
      </a>
    </div>
  <?php endforeach; ?>

  <!-- Controls -->
  <button id="banner-prev" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center text-white transition-colors z-10">
    <i data-lucide="chevron-left" class="w-[18px] h-[18px]"></i>
  </button>
  <button id="banner-next" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center text-white transition-colors z-10">
    <i data-lucide="chevron-right" class="w-[18px] h-[18px]"></i>
  </button>

  <!-- Dots -->
  <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10" id="banner-dots">
    <?php for ($i = 0; $i < count($banners); $i++): ?>
      <button class="banner-dot w-2 h-2 rounded-full transition-all <?= $i === 0 ? 'bg-white w-6' : 'bg-white/50' ?>"></button>
    <?php endfor; ?>
  </div>
</div>

<!-- Main content -->
<div class="max-w-[1200px] mx-auto px-4 py-8 space-y-12">

  <!-- Flash Sale -->
  <section>
    <div class="bg-gradient-to-r from-red-500 to-orange-500 rounded-t-xl px-5 py-3 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <i data-lucide="zap" class="w-5 h-5 text-white fill-white"></i>
        <span class="text-white font-bold text-lg">FLASH SALE</span>
      </div>
      <div class="flex items-center gap-2 text-white" id="flash-timer">
        <i data-lucide="clock" class="w-4 h-4"></i>
        <span class="text-sm">Kết thúc trong:</span>
        <span class="bg-white/20 rounded px-2 py-0.5 text-sm font-mono font-bold" id="t-h">05</span>:
        <span class="bg-white/20 rounded px-2 py-0.5 text-sm font-mono font-bold" id="t-m">23</span>:
        <span class="bg-white/20 rounded px-2 py-0.5 text-sm font-mono font-bold" id="t-s">45</span>
      </div>
    </div>
    <div class="bg-white rounded-b-xl p-5 shadow-sm">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php
        $saleBooks = [
          ['title'=>'Đắc Nhân Tâm','author'=>'Dale Carnegie','price'=>'45.000 ₫','orig'=>'68.000 ₫','tag'=>'SALE','pct'=>'-34%'],
          ['title'=>'Nhà Giả Kim','author'=>'Paulo Coelho','price'=>'55.000 ₫','orig'=>'79.000 ₫','tag'=>'SALE','pct'=>'-30%'],
          ['title'=>'Tôi Tài Giỏi','author'=>'Adam Khoo','price'=>'59.000 ₫','orig'=>'85.000 ₫','tag'=>'SALE','pct'=>'-31%'],
          ['title'=>'Atomic Habits','author'=>'James Clear','price'=>'65.000 ₫','orig'=>'95.000 ₫','tag'=>'SALE','pct'=>'-32%'],
        ];
        foreach ($saleBooks as $book): ?>
          <div class="group relative bg-white border border-gray-100 rounded-xl overflow-hidden hover:shadow-md transition-shadow cursor-pointer">
            <div class="absolute top-2 left-2 z-10">
              <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $book['pct'] ?></span>
            </div>
            <div class="h-40 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
              <i data-lucide="book" class="w-12 h-12 text-gray-400"></i>
            </div>
            <div class="p-3">
              <p class="text-sm font-medium text-[#333] truncate"><?= $book['title'] ?></p>
              <p class="text-xs text-gray-400 mb-2"><?= $book['author'] ?></p>
              <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-[#4CAF50]"><?= $book['price'] ?></span>
                <span class="text-xs text-gray-400 line-through"><?= $book['orig'] ?></span>
              </div>
              <button class="mt-2 w-full py-1.5 bg-[#4CAF50] hover:bg-[#43A047] text-white text-xs rounded-lg transition-colors">
                Thêm giỏ hàng
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Sách mới -->
  <section>
    <div class="flex items-center gap-2 mb-6 pb-3 border-b-2 border-[#4CAF50]">
      <i data-lucide="sparkles" class="w-5 h-5 text-[#4CAF50]"></i>
      <h2 class="text-xl font-bold text-[#333]">SÁCH MỚI</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <?php
      $newBooks = [
        ['title'=>'Sapiens','author'=>'Yuval Noah Harari','price'=>'120.000 ₫','tag'=>'NEW'],
        ['title'=>'Homo Deus','author'=>'Yuval Noah Harari','price'=>'115.000 ₫','tag'=>'NEW'],
        ['title'=>'21 Bài Học','author'=>'Yuval Noah Harari','price'=>'109.000 ₫','tag'=>'NEW'],
        ['title'=>'Mindset','author'=>'Carol S. Dweck','price'=>'89.000 ₫','tag'=>'NEW'],
      ];
      foreach ($newBooks as $book): ?>
        <div class="group bg-white border border-gray-100 rounded-xl overflow-hidden hover:shadow-md transition-shadow cursor-pointer">
          <div class="relative h-40 bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
            <span class="absolute top-2 left-2 bg-[#4CAF50] text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $book['tag'] ?></span>
            <i data-lucide="book" class="w-12 h-12 text-[#4CAF50]/50"></i>
          </div>
          <div class="p-3">
            <p class="text-sm font-medium text-[#333] truncate"><?= $book['title'] ?></p>
            <p class="text-xs text-gray-400 mb-2"><?= $book['author'] ?></p>
            <span class="text-sm font-bold text-[#4CAF50]"><?= $book['price'] ?></span>
            <button class="mt-2 w-full py-1.5 border border-[#4CAF50] text-[#4CAF50] hover:bg-[#4CAF50] hover:text-white text-xs rounded-lg transition-colors">
              Thêm giỏ hàng
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Sách bán chạy -->
  <section>
    <div class="flex items-center gap-2 mb-6 pb-3 border-b-2 border-[#FFC107]">
      <i data-lucide="trending-up" class="w-5 h-5 text-[#FFC107]"></i>
      <h2 class="text-xl font-bold text-[#333]">SÁCH BÁN CHẠY</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <?php
      $hotBooks = [
        ['title'=>'Đắc Nhân Tâm','author'=>'Dale Carnegie','price'=>'68.000 ₫','sold'=>1240],
        ['title'=>'Nhà Giả Kim','author'=>'Paulo Coelho','price'=>'79.000 ₫','sold'=>987],
        ['title'=>'Tôi Tài Giỏi','author'=>'Adam Khoo','price'=>'85.000 ₫','sold'=>854],
        ['title'=>'Think and Grow Rich','author'=>'Napoleon Hill','price'=>'72.000 ₫','sold'=>811],
      ];
      foreach ($hotBooks as $book): ?>
        <div class="group bg-white border border-gray-100 rounded-xl overflow-hidden hover:shadow-md transition-shadow cursor-pointer">
          <div class="h-40 bg-gradient-to-br from-yellow-50 to-orange-50 flex items-center justify-center">
            <i data-lucide="book" class="w-12 h-12 text-yellow-400/60"></i>
          </div>
          <div class="p-3">
            <p class="text-sm font-medium text-[#333] truncate"><?= $book['title'] ?></p>
            <p class="text-xs text-gray-400 mb-1"><?= $book['author'] ?></p>
            <p class="text-xs text-gray-400 mb-2">Đã bán: <?= number_format($book['sold']) ?></p>
            <span class="text-sm font-bold text-[#4CAF50]"><?= $book['price'] ?></span>
            <button class="mt-2 w-full py-1.5 bg-[#FFC107] hover:bg-[#FFB300] text-[#333] text-xs rounded-lg font-medium transition-colors">
              Thêm giỏ hàng
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Danh mục nổi bật -->
  <section>
    <div class="flex items-center gap-2 mb-6 pb-3 border-b-2 border-purple-500">
      <span class="text-xl">📂</span>
      <h2 class="text-xl font-bold text-[#333]">DANH MỤC NỔI BẬT</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
      <?php
      $featCats = [
        ['icon'=>'📖','name'=>'Văn học',      'count'=>'1,240 cuốn', 'color'=>'from-blue-50 to-blue-100',   'border'=>'border-blue-200'],
        ['icon'=>'💼','name'=>'Kinh tế',      'count'=>'856 cuốn',   'color'=>'from-green-50 to-green-100', 'border'=>'border-green-200'],
        ['icon'=>'🧒','name'=>'Thiếu nhi',    'count'=>'620 cuốn',   'color'=>'from-yellow-50 to-yellow-100','border'=>'border-yellow-200'],
        ['icon'=>'🌟','name'=>'Kỹ năng sống', 'count'=>'743 cuốn',   'color'=>'from-purple-50 to-purple-100','border'=>'border-purple-200'],
        ['icon'=>'🔬','name'=>'Khoa học',     'count'=>'512 cuốn',   'color'=>'from-cyan-50 to-cyan-100',   'border'=>'border-cyan-200'],
        ['icon'=>'📜','name'=>'Lịch sử',      'count'=>'389 cuốn',   'color'=>'from-orange-50 to-orange-100','border'=>'border-orange-200'],
      ];
      foreach ($featCats as $cat): ?>
        <a href="<?= BASE_URL ?>?act=books&category=<?= urlencode($cat['name']) ?>"
           class="flex items-center gap-4 p-5 bg-gradient-to-r <?= $cat['color'] ?> border <?= $cat['border'] ?> rounded-xl hover:shadow-md transition-shadow">
          <span class="text-3xl"><?= $cat['icon'] ?></span>
          <div>
            <p class="font-semibold text-[#333]"><?= $cat['name'] ?></p>
            <p class="text-sm text-gray-500"><?= $cat['count'] ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Email signup banner -->
  <section class="rounded-xl overflow-hidden bg-gradient-to-r from-[#4CAF50] to-[#1B5E20] p-8 flex flex-col md:flex-row items-center justify-between gap-6">
    <div class="text-white">
      <h3 class="text-2xl font-bold mb-2">Đăng ký nhận ưu đãi</h3>
      <p class="text-white/80">Nhận thông báo sách mới &amp; voucher giảm giá độc quyền</p>
    </div>
    <div class="flex gap-2 w-full md:w-auto">
      <input type="email" placeholder="Nhập email của bạn"
             class="flex-1 md:w-64 px-4 py-2.5 rounded-lg outline-none text-sm">
      <button class="bg-[#FFC107] hover:bg-[#FFB300] text-[#333] px-5 py-2.5 rounded-lg font-semibold transition-colors whitespace-nowrap">
        Đăng ký
      </button>
    </div>
  </section>

</div>

<?php require_once './views/components/customer_footer.php'; ?>

<!-- Banner slider JS -->
<script>
(function() {
  const slides = document.querySelectorAll('.banner-slide');
  const dots   = document.querySelectorAll('.banner-dot');
  let cur = 0;

  function go(n) {
    slides[cur].classList.remove('opacity-100','relative');
    slides[cur].classList.add('opacity-0','pointer-events-none');
    dots[cur].classList.remove('bg-white','w-6');
    dots[cur].classList.add('bg-white/50');
    cur = (n + slides.length) % slides.length;
    slides[cur].classList.remove('opacity-0','pointer-events-none');
    slides[cur].classList.add('opacity-100','relative');
    dots[cur].classList.remove('bg-white/50');
    dots[cur].classList.add('bg-white','w-6');
  }

  document.getElementById('banner-prev')?.addEventListener('click', () => go(cur - 1));
  document.getElementById('banner-next')?.addEventListener('click', () => go(cur + 1));
  dots.forEach((d, i) => d.addEventListener('click', () => go(i)));
  setInterval(() => go(cur + 1), 4000);

  // Flash sale timer
  let h=5,m=23,s=45;
  setInterval(() => {
    s--; if(s<0){s=59;m--;} if(m<0){m=59;h--;} if(h<0){h=23;}
    document.getElementById('t-h').textContent = String(h).padStart(2,'0');
    document.getElementById('t-m').textContent = String(m).padStart(2,'0');
    document.getElementById('t-s').textContent = String(s).padStart(2,'0');
  }, 1000);
})();
</script>
