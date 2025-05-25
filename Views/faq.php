<?php include __DIR__ . '/../header.php'; ?>

<head>
  <meta charset="UTF-8">
  <title>FAQ</title>
  <link rel="stylesheet" href="../styles/style.css">
  <link rel="stylesheet" href="../styles/faq.css">
</head>
<body>
<main>
  <h1>BESOIN D'AIDE ?</h1>

  <div class="faq">
    <?php if (!empty($faqItems)): ?>
      <?php foreach ($faqItems as $item): ?>
        <div class="faq-item">
          <div class="faq-question"><?= htmlspecialchars($item['question']) ?></div>
          <div class="faq-answer">
            <p><?= $item['answer'] ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Aucune question disponible pour le moment.</p>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../footer.php'; ?>

<script>
  document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', () => {
      const item = question.parentElement;
      item.classList.toggle('active');
    });
  });
</script>
</body>