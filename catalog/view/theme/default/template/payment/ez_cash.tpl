<form class="form-horizontal" method="post" action="https://ipg.dialog.lk/ezCashIPGExtranet/servlet_sentinal">
	<fieldset id="payment">
		<input type="hidden" value="<?php echo $invoice; ?>" name="merchantInvoice">
	</fieldset>
	<div class="buttons">
	  <div class="pull-right">
		<input type="submit" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" />
	  </div>
	</div>
</form>

