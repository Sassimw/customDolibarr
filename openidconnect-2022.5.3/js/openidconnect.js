if($) {
	$(window).on('unload', function() {
		if($.cookie) {
			if(! document.location.pathname.endsWith('/openidconnect/public/callback.php')) {
				let params = new URLSearchParams(document.location.search);
				params.delete('openid_mode');
				$.cookie("rollback_url", `${document.location.pathname}?${params.toString()}`, { expires: 1, path: '/' });
			}
		}
	});
}
