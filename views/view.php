<h1><?php echo $page->title; ?></h1>
<p>
   <?php echo $page->content; ?> 
</p>

<?php if ($comments): ?>
<ul>
    <?php foreach($page->comments as $comment): ?>
    <li><?php echo $comment->content?></li>
    <?php endforeach; ?>
</ul>

<?php echo \Request::factory('comments/comments/add/' . $page->section . '/' . $page->id, false)->execute(); ?>

<?php endif; ?>