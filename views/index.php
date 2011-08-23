<?php foreach($pages as $page): ?>

<h2><?php echo $page->title; ?> - Comments <?php echo $page->comment; ?></h2>
<p>
   <?php echo $page->content; ?> 
</p>

<?php endforeach; ?>