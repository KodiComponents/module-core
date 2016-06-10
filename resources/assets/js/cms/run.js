$(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	CMS.Translate.init(function () {
		CMS.ui.init();
		KodiCMS.start(null, CMS.settings);

		CMS.controllers.call();
		CMS.messages.init();
		CMS.Notifications.init();
	});

	window.App = new Vue({
		el: 'body',
		data: function () {
			return {
				loadingNotifications: false,
				notifications: []
			}
		},
		created: function () {
			var self = this;
			$('#notifications')
				.on('hidden.bs.dropdown', function () {
					$('.notifications-list').slimScroll({ destroy: true });
					self.markNotificationsAsRead();
				})
				.on('shown.bs.dropdown', function () {
					self.showNotifications();
				});

			this.loadDataForAuthenticatedUser();
		},
		methods: {
			showNotifications: function () {
				this.loadDataForAuthenticatedUser();
			},

			loadDataForAuthenticatedUser: function () {
				this.getNotifications();
			},

			/**
			 * Get the application notifications.
			 */
			getNotifications: function () {
				this.loadingNotifications = true;

				var self = this;

				this.$http.get('/api.notifications.recent').then(function (response) {
					this.notifications = response.data.content;
					this.loadingNotifications = false;
					$('.notifications-list').slimScroll({ height: 250 });
				}, function (response) {
					// error callback
				});
			},

			markNotificationAsRead: function (notification) {
				if (notification.read) {
					return;
				}

				this.$http.put('/api.notifications.read', {ids: [notification.id]});
				notification.read = true;
			},

			/**
			 *
			 * Mark the current notifications as read.
			 */
			markNotificationsAsRead: function () {
				if (!this.hasUnreadNotifications) {
					return;
				}

				this.$http.put('/api.notifications.read', {ids: _.pluck(this.notifications, 'id')});

				_.each(this.notifications, function (notification) {
					notification.read = true;
				});
			}
		},
		computed: {
			unreadNotifications: function () {
				if (this.notifications) {
					return _.filter(this.notifications, function (notification) {
						return !notification.read;
					}).length;
				}

				return 0;
			},

			hasNotifications: function () {
				return this.notifications.length;
			},

			/**
			 * Determine if the user has any unread notifications.
			 */
			hasUnreadNotifications: function () {
				return this.unreadNotifications > 0;
			}
		}
	});

});
