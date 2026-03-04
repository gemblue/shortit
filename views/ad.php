<?php include __DIR__ . '/header.php'; ?>
<div class="max-w-xl mx-auto text-center mt-12">
  <div class="mb-4" id="banner">
    <?= $banner_html ?>
  </div>
  <div class="text-gray-600 mb-6">Redirecting in <span id="countdown"><?=intval($delay)?></span> seconds...</div>
  <div><a href="<?=htmlspecialchars($target)?>" class="text-blue-600">Go now</a></div>
</div>
<script>
let sec = <?=intval($delay)?>;
let el = document.getElementById('countdown');
let t = setInterval(function(){
    sec--;
    if(sec<=0){ clearInterval(t); window.location='<?=addslashes($target)?>'; }
    el.textContent = sec;
},1000);
</script>
<?php include __DIR__ . '/footer.php'; ?>
