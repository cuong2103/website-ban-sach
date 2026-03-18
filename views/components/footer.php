    </div><!-- /.flex-1.ml-64 -->

<?php if ($success = Message::get('success')): ?>
  <div id="simple-toast"
    class="fixed top-[4.5rem] right-5 z-50 w-96 bg-white border-2 border-green-400 bg-green-100 rounded-lg shadow-lg p-4 pb-0 max-w-[400px] flex flex-col gap-2 opacity-0 translate-x-8 transition-all duration-300 ease-out">
    <div class="flex items-center justify-between gap-3">
      <div class="flex items-center gap-3">
        <i class="w-5 h-5 text-green-700" data-lucide="check"></i>
        <span class="text-green-700 font-medium text-sm"><?= $success ?></span>
      </div>
      <button onclick="this.closest('#simple-toast').remove()" class="text-gray-400 hover:text-gray-600">
        <i class="w-5 h-5 text-green-700" data-lucide="x"></i>
      </button>
    </div>
    <div class="h-1 w-full -translate-x-4 rounded-full overflow-hidden">
      <div id="toast-progress" class="h-1 bg-green-400 w-full transition-all"></div>
    </div>
  </div>
  <script>
    {
      const t = document.getElementById('simple-toast');
      const p = document.getElementById('toast-progress');
      if (t && p) {
        setTimeout(() => { t.classList.remove('opacity-0','translate-x-8'); t.classList.add('opacity-100','translate-x-0'); }, 10);
        p.style.transition = "width 5s linear";
        setTimeout(() => { p.style.width = "0%"; }, 20);
        setTimeout(() => { t.classList.add('opacity-0','translate-x-8'); t.addEventListener('transitionend', () => t.remove()); }, 5000);
      }
    }
  </script>
<?php endif; ?>

<?php if ($error = Message::get('error')): ?>
  <div id="simple-toast"
    class="fixed top-[4.5rem] right-5 z-50 w-96 max-w-[400px] bg-red-100 border-2 border-red-400 rounded-lg shadow-lg p-4 pb-0 flex flex-col gap-2 opacity-0 translate-x-8 transition-all duration-300 ease-out">
    <div class="flex items-center justify-between gap-3">
      <div class="flex items-center gap-2">
        <i class="w-5 h-5 text-red-700" data-lucide="triangle-alert"></i>
        <span class="text-red-700 font-medium text-sm"><?= $error ?></span>
      </div>
      <button onclick="this.closest('#simple-toast').remove()" class="text-gray-400 hover:text-gray-600">
        <i class="w-5 h-5 text-red-700" data-lucide="x"></i>
      </button>
    </div>
    <div class="h-1 w-full -translate-x-4 rounded-full overflow-hidden">
      <div id="toast-progress" class="h-1 bg-red-400 w-full transition-all"></div>
    </div>
  </div>
  <script>
    const t = document.getElementById('simple-toast');
    const p = document.getElementById('toast-progress');
    if (t && p) {
      setTimeout(() => { t.classList.remove('opacity-0','translate-x-8'); t.classList.add('opacity-100','translate-x-0'); }, 10);
      p.style.transition = "width 5s linear";
      setTimeout(() => { p.style.width = "0%"; }, 20);
      setTimeout(() => { t.classList.add('opacity-0','translate-x-8'); t.addEventListener('transitionend', () => t.remove()); }, 5000);
    }
  </script>
<?php endif; ?>

</body>
<script>lucide.createIcons();</script>
</html>
