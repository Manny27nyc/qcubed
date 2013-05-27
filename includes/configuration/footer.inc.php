<?php
	// This example footer.inc.php is intended to be modfied for your application.
?>
		</section>
		<footer class="ui-widget ui-widget-content ui-corner-all">
			<div id="tagline"><a href="http://qcubed.github.com/" title="QCubed Homepage"><img id="logo" src="<?php _p(__VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__ . '/qcubed_logo_footer.png'); ?>" alt="QCubed Framework" /> <span class="version"><?php _p(QCUBED_VERSION); ?></span></a></div>
		</footer>
<script type="text/javascript">
if ( 1 == footer_use ) {
	window.onbeforeunload = function (e) { 
		var e = e || window.event; 
		var myMessage= "Нажмите ОК для корректного выхода оператора"; 
		//  Internet Explorer & Firefox 
		qc.pA('RequestListForm', 'operatorLogout', 'QClickEvent', '', '');
		if (e) { 
			e.returnValue = myMessage; 
		} 
		return myMessage; 
	}; 
}
</script>
	</body>
</html>