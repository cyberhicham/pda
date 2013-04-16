<div class="row">
@if (count($errors->all()) > 0)
<div class="alert alert-error alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4>Error</h4>
	Please check the form bellow for errors
</div>
@endif

@if ($message = Session::get('success'))
<!-- <div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	{{{ $message }}}
</div> -->
<script type="text/javascript">
	$(document).ready( function() {
		alertify.success( "<?php echo $message; ?>" );
		setTimeout(function() {
		      $('.alert').fadeOut();
		}, 1000);
	});
</script>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-error fade-in">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	{{{ $message }}}
</div>
<script type="text/javascript">
	$(document).ready( function() {
		alertify.error( "<?php echo $message; ?>" );
		$('.alert').on('click', function() {
		  $('.alert').fadeOut();
		});
	});
</script>
@endif
@if( $message = Session::get('errorAlert') )
<script type="text/javascript">
	$(document).ready( function() {
		alertify.error( "<?php echo $message; ?>" );
	});
</script>
@endif

@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4>Warning</h4>
	{{{ $message }}}
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert alert-info alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4>Info</h4>
	{{{ $message }}}
</div>
@endif
@if ( isset($notice) )
<div class="alert alert-info alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	{{ $notice }}
	<script type="text/javascript">
		$(document).ready( function() {
			alertSuccess( "<?php echo $message; ?>" );
			setTimeout(function() {
			      $('.alert').fadeOut();
			}, 1000);
		});
	</script>

</div>
@endif


</div>
