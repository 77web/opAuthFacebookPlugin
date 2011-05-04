<h2><?php echo __('opAuthWithTwitterPlugin setting'); ?></h2>
<form action="<?php echo url_for('opAuthFacebookPlugin/config') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Save') ?>" /></td>
</tr>
</table>
</form>
<?php echo link_to(__('Back'), 'plugin/list?type=auth') ?>