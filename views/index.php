<?php foreach($pages as $page): ?>

<h2><?php echo $page->title; ?></h2>
<p>
   <?php echo $page->content; ?> 
</p>

<?php endforeach; ?>