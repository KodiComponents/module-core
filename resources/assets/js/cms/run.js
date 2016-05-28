$(function() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	CMS.Translate.init(function() {
		CMS.ui.init();
		KodiCMS.start(null, CMS.settings);

		CMS.controllers.call();
		CMS.messages.init();
		CMS.Notifications.init();
	});
});
