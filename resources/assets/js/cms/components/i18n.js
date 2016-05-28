CMS.Translate = {
	init: function (callback) {
		try {
			i18n.init({
				lng: LOCALE,
				fallbackLng: 'ru',
				interpolationPrefix: ':',
				interpolationSuffix: '',
				resGetPath: '/cms/js/locale/:lng.json'
			}, callback);
		} catch (err) {
			callback();
		}
	},
	trans: function (key, options) {
		return i18n.t(key, options);
	}
}

window.trans = function (key, options) {
	return CMS.Translate.trans(key, options)
}
