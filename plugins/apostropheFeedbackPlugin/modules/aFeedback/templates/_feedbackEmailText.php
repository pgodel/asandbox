<?php use_helper('a') ?>
<?php echo a_('URL where the problem occured:') ?> http://<?php echo $_SERVER['HTTP_HOST'] ?><?php echo $feedback['section'] ?> 

<?php echo $feedback['description'] ?>

<?php echo $feedback['browser'] ?> 
