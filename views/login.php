<?php include __DIR__ . '/header.php'; ?>
<div class="max-w-md mx-auto mt-12">
  <form method="post" class="bg-white p-6 rounded shadow">
    <h2 class="text-lg font-medium mb-4">Welcome back, why shorting is so urgent? Calm down, you're in control.</h2>
    <?php if(!empty($error)): ?><div class="text-red-600 mb-3"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <label class="block mb-2">Username
      <input name="user" class="mt-1 block w-full border rounded px-2 py-1" />
    </label>
    <label class="block mb-4">Password
      <input name="pass" type="password" class="mt-1 block w-full border rounded px-2 py-1" />
    </label>
    <div class="flex justify-end">
      <button class="bg-blue-600 text-white px-4 py-2 rounded">Login</button>
    </div>
  </form>
</div>
<?php include __DIR__ . '/footer.php'; ?>
