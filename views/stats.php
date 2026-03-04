<?php include __DIR__ . '/header.php'; ?>
<div class="max-w-4xl mx-auto">
  <a href="/admin" class="text-sm text-gray-500">← Back</a>
  <h2 class="text-xl font-semibold mt-4 mb-4">Stats</h2>

  <div class="bg-white rounded shadow p-4 mb-6">
    <h3 class="font-medium mb-2">All Links</h3>
    <table class="w-full text-left text-sm">
      <thead class="text-gray-500"><tr><th>Slug</th><th>Title</th><th>Clicks</th><th></th></tr></thead>
      <tbody>
      <?php foreach($links as $l): ?>
        <tr class="border-t"><td class="py-2">/s/<?=htmlspecialchars($l['slug'])?></td><td class="py-2"><?=htmlspecialchars($l['title'])?></td><td class="py-2"><?=intval($l['clicks'])?></td><td class="py-2"><a href="/admin/stats?link=<?=intval($l['id'])?>">view</a></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if(!empty($selected_link)): ?>
  <div class="bg-white rounded shadow p-4">
    <h3 class="font-medium mb-2">Recent Clicks for /s/<?=htmlspecialchars($selected_link['slug'])?></h3>
    <table class="w-full text-left text-sm">
      <thead class="text-gray-500"><tr><th>Time</th><th>IP</th><th>Referer</th><th>UA</th></tr></thead>
      <tbody>
      <?php foreach($clicks as $c): ?>
        <tr class="border-t"><td class="py-2"><?=htmlspecialchars($c['created_at'])?></td><td class="py-2"><?=htmlspecialchars($c['ip'])?></td><td class="py-2"><?=htmlspecialchars($c['referer'])?></td><td class="py-2"><?=htmlspecialchars(substr($c['ua'],0,80))?></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

</div>
<?php include __DIR__ . '/footer.php'; ?>
