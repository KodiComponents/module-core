var elixir = require('laravel-elixir');
elixir.extend('sourcemaps', false);

process.env.DISABLE_NOTIFIER = true;

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
	mix
		.less('common.less', 'public/cms/css/app.css')
		.less('kodicms/jquery-ui/jquery-ui.less', 'public/cms/css/jquery-ui.css');

	/**************************************************************
	 * Libraries
	 **************************************************************/
	mix
		.scripts([
			'libs/jquery/js/jquery.min.js',
			'libs/bootstrap/js/bootstrap.js',
			'libs/noty/js/jquery.noty.packaged.js',
			'libs/select2/js/select2.full.js',
			'libs/jquery-colorbox/js/jquery.colorbox-min.js',
			'libs/bootstrap-toggle/js/bootstrap-toggle.min.js',
			'libs/jquery-validation/js/jquery.validate.js',
			'libs/jquery-validation/js/additional-methods.js',
			'libs/dropzone/js/dropzone.min.js',
			'libs/php-date-formatter/js/php-date-formatter.js',
			'libs/datetimepicker/js/jquery.datetimepicker.js',
			'libs/underscore/js/underscore-min.js',
			'libs/moment/js/moment.min.js',
			'libs/fastclick/js/fastclick.js',
			'libs/slimScroll/js/jquery.slimscroll.min.js',
			'libs/jquery-query-object/js/jquery.query-object.js',
			'libs/bootbox.js/js/bootbox.js',
			'libs/i18next/js/i18next.min.js',
			'libs/vue/js/vue.js',
			'libs/vue-resource/js/vue-resource.js'
		], 'public/cms/js/libraries.js', 'public/cms/');

	/**************************************************************
	 * Backend
	 **************************************************************/
	mix
		.scripts([
			'cms/core.js',
			'cms/app.js',
			'cms/components/messages.js',
			'cms/components/filters.js',
			'cms/components/loader.js',
			'cms/components/notifications.js',
			'cms/components/filemanager.js',
			'cms/components/controllers.js',
			'cms/components/ui.js',
			'cms/components/i18n.js',
			'cms/helpers.js',
			'cms/ui.js',
			'cms/hashString.js',
			'cms/popup.js',
			'cms/scroll.js',
			'cms/api.js',
			'cms/user.meta.js',
			'cms/run.js'
		], 'public/cms/js/backend.js')
		.scripts(['wysiwyg/ace.js'], 'public/cms/js/wysiwyg/ace.js')
		.scripts(['wysiwyg/ckeditor.js'], 'public/cms/js/wysiwyg/ckeditor.js');

	/**************************************************************
	 * Page Wysiwyg Libraries
	 **************************************************************/
	mix
		.less(
			'custom/page-wysiwyg.less', 'public/cms/css/page-wysiwyg.css'
		)
		.scripts([
			'libs/jquery/js/jquery.min.js',
			'libs/sortable/js/Sortable.min.js',
			'libs/sortable/js/jquery.binding.js',
			'libs/jquery-colorbox/js/jquery.colorbox-min.js',
			'libs/jquery-query-object/js/jquery.query-object.js',
			'libs/underscore/js/underscore-min.js',
		], 'public/cms/js/page-wysiwyg-libraries.js', 'public/cms/')
		.scripts([
			'cms/app.js',
			'cms/components/ui.js',
			'cms/popup.js',
			'cms/api.js',
			'cms/page-wysiwyg.js'
		], 'public/cms/js/page-wysiwyg.js');

	/**************************************************************
	 * Query Builder
	 **************************************************************/
	mix
		.scripts([
			'jQuery.extendext.js',
			'doT.js',
			'main.js',
			'defaults.js',
			'core.js',
			'public.js',
			'data.js',
			'template.js',
			'model.js',
			'utils.js',
			'jquery.js',
			'fields/types/core.js',
			'fields/types/checkbox.js',
			'fields/types/datetime.js',
			'fields/types/number.js',
			'fields/types/select.js',
			'fields/types/textarea.js',
			'fields/core.js',
			'plugins/sortable.js'
		], 'public/cms/libs/query-builder/query-builder.js', 'resources/assets/js/query-builder')
		.less(
			'query-builder/default.less', 'public/cms/libs/query-builder/query-builder.css'
		);
});
