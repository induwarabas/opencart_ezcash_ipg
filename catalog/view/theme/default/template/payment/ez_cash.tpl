<form class="form-horizontal" method="post" action="https://ipg.dialog.lk/ezCashIPGExtranet/servlet_sentinal">
	<fieldset id="payment">
		<!--<legend>Payment</legend>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="input-ez-cash-source">Payment method</label>
			<div class="col-sm-10">
				<select name="ez_cash_source" id="input-ez-cash-source" class="form-control">
					<?php foreach ($ez_cash_sources as $source) { ?>
					<option value="<?php echo $source; ?>"><?php echo $source; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>-->
		<input type="hidden" value="<?php echo $invoice; ?>" name="merchantInvoice">
		<!--<div class="form-group">
			<label class="col-sm-2 control-label" for="input-ez-reference">Reference ID</label>
			<div class="col-sm-10">
				<input type="text" name="ez_cash_merchant_id" value="<?php echo $ez_cash_merchant_id; ?>" id="input-ez-cash-merchant-id" class="form-control" />
			</div>
		</div>-->
	</fieldset>

<div class="buttons">
  <div class="pull-right">
    <input type="submit" value="Pay with ezCash" id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" />
  </div>
</div>
</form>
<script type="text/javascript"><!--
//$('#button-confirm').on('click', function() {
//	$.ajax({
//		type: 'post',
//		data: $('#payment :input'),
//		url: 'index.php?route=payment/ez_cash/confirm',
//		cache: false,
//		beforeSend: function() {
//			$('#button-confirm').button('loading');
//		},
//		complete: function() {
//			$('#button-confirm').button('reset');
//		},
//		success: function() {
//			location = '<?php echo $continue; ?>';
//		}
//	});
//});
//--></script>
