<script src=" https://code.jquery.com/jquery-3.4.1.min.js "></script>
<script src="js/common.js"></script>
<?php if (isset($js_file)): ?>
  <?php if (is_array($js_file)): ?>
    <?php foreach ($js_file as $file): ?>
      <script src="js/<?= $file?>.js"></script>
    <?php endforeach; ?>
  <?php else: ?>
    <script src="js/<?= $js_file?>.js"></script>
  <?php endif; ?>
<?php endif ?>
</body>
</html>
