CMS.ui = {

	// 0: name
	// 1: callback
	// 2: priority

	_elements: [],
	add: function (module, callback, priority) {
		if (!_.isFunction(callback))
			return this;

		CMS.ui._elements.push([module, callback, priority || 0]);
		return this;
	},
	call: function (module) {
		for (var i = 0; i < CMS.ui._elements.length; i++) {
			var elm = CMS.ui._elements[i];
			if (_.isArray(module) && _.indexOf(module, elm[0]) != -1)
				elm[1]();
			else if (module == elm[0])
				elm[1]();
		}
	},
	init: function (module, container) {
		if (_.isUndefined(container)) {
			var container = $('body')
		} else if (!(container instanceof jQuery)) {
			if (_.isString(container))
				container = $(container);
			else
				throw new TypeError('Container must be string or jQuery object');
		}

		CMS.ui._elements = _.sortBy(CMS.ui._elements, 2);

		for (var i = 0; i < CMS.ui._elements.length; i++) {
			var elm = CMS.ui._elements[i];

			try {
				if (_.isUndefined(module))
					elm[1](container);
				else if (_.isArray(module) && _.indexOf(module, elm[0]) != -1)
					elm[1](container);
				else if (_.isString(module) && module == elm[0])
					elm[1](container);
			} catch (e) {
				console.log(elm[0], e);
			}
		}
	}
};
