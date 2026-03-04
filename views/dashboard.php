<?php include __DIR__ . '/header.php'; ?>
<div class="flex justify-between items-center mb-4">
  <h2 class="text-xl font-semibold">Dashboard</h2>
  <div>
    <a href="/admin/create" class="mr-3 text-blue-600">Create</a>
    <a href="/admin/stats" class="mr-3 text-gray-600">Stats</a>
    <a href="/admin/logout" class="text-red-600">Logout</a>
  </div>
</div>

<div class="bg-white shadow rounded p-4">
  <h3 class="font-medium mb-3">Your Links</h3>
  <table class="w-full text-left text-sm">
    <thead class="text-gray-500"><tr><th>Slug</th><th>Title</th><th>Clicks</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach($links as $l): ?>
      <tr class="border-t">
        <td class="py-2"><a href="/s/<?=htmlspecialchars($l['slug'])?>" target="_blank" class="text-blue-600">/s/<?=htmlspecialchars($l['slug'])?></a></td>
        <td class="py-2"><?=htmlspecialchars($l['title'])?></td>
        <td class="py-2"><?=intval($l['clicks'])?></td>
        <td class="py-2"><a href="/admin/stats?link=<?=intval($l['id'])?>" class="text-gray-600">view</a> | <a href="/admin/edit?link=<?=intval($l['id'])?>" class="text-yellow-600">edit</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/footer.php'; ?>
