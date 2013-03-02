<div id="bucket-shortcode-display">
	<?php $pid = $GLOBALS['post_ID'];
	$title = get_the_title($pid); ?>
		<p>Copy and paste this shortcode into any Post, Page or even another Bucket. Yeah that's right... a Bucket within a Bucket.</p>
		<div class="bucket-shortcode">
			
			<span class="bucket-shortcode-tooltip">Click to Copy!</span>
			<a href="#" id="copy">Copy to Clipboard</a>
			<span id="cody">[bucket id="<?php echo $pid; ?>" title="<?php echo $title; ?>"]</span>
			<span>[bucket id="<?php echo $pid; ?>" title="<?php echo $title; ?>"]</span>
		</div>
</div>