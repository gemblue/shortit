<?php include __DIR__ . '/header.php'; ?>
<div class="max-w-xl mx-auto">
  <a href="/admin" class="text-sm text-gray-500">← Back</a>
  <form method="post" class="bg-white p-6 rounded shadow mt-4">
    <h2 class="text-lg font-medium mb-4">Create Shortlink</h2>
    <?php if(!empty($msg)): ?><div class="text-green-600 mb-3"><?=htmlspecialchars($msg)?></div><?php endif; ?>
    <?php if(!empty($error)): ?><div class="text-red-600 mb-3"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <label class="block mb-2">Title
      <input name="title" value="<?=htmlspecialchars($title ?? '')?>" class="mt-1 block w-full border rounded px-2 py-1" />
    </label>
    <label class="block mb-2">Target URL
      <input name="url" value="<?=htmlspecialchars($url ?? '')?>" class="mt-1 block w-full border rounded px-2 py-1" />
    </label>
    <label class="block mb-4">Custom Slug (optional)
      <input name="slug" value="<?=htmlspecialchars($slug ?? '')?>" class="mt-1 block w-full border rounded px-2 py-1" />
    </label>
    <div class="flex justify-end">
      <button class="bg-green-600 text-white px-4 py-2 rounded">Create</button>
    </div>
  </form>
</div>
<?php include __DIR__ . '/footer.php'; ?>
