<div id="main-navbar" class="navbar" role="navigation">

	<button type="button" id="main-menu-toggle"><i class="navbar-icon fa fa-bars icon"></i>
		<span class="hide-menu-text">@lang('cms::core.navigation.hide')</span>
	</button>

	<div class="navbar-inner">

		<div class="navbar-header">
			{!! link_to(backend_url_segment(), config('cms.logo'), ['class' => 'navbar-brand']) !!}
		</div>

		<div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
			<div>
				@event('view.navbar.left')

				<div class="right clearfix">

					<ul class="nav navbar-nav pull-right right-navbar-nav">

						@event('view.navbar.right.before')

						<li class="nav-icon-btn nav-icon-btn-danger dropdown" id="notifications">
							<a href="#notifications" data-toggle="dropdown">
								<span class="label">@{{ unreadNotifications }}</span>
								<i class="nav-icon fa fa-bullhorn"></i>
								<span class="small-screen-text">Notifications</span>
							</a>
							<div class="dropdown-menu widget-notifications no-padding" style="width: 300px">
								<div class="notifications-list" v-show="hasNotifications">
									<div class="notification" v-for="notification in notifications" :class="{'read': notification.read}">
										<div class="notification-description">
											@{{{ notification.body }}}

											<div class="link" v-if="notification.action_text">
												<a :href="notification.action_url">@{{ notification.action_text }}</a>
											</div>

											<div class="creator" v-if="notification.creator">
												@lang('cms::core.notification.field.creator'): @{{{ notification.creator.name }}}
											</div>
										</div>
										<div class="notification-ago">@{{ notification.created_at | relative }}</div>
										<div class="notification-icon fa fa-@{{ notification.icon }}"></div>
									</div>
								</div>
							</div>
						</li>

						@can('system::view_settings')
						<li>
							<a href="{{ route('backend.settings') }}">{!! UI::icon('cogs fa-lg') !!}</a>
						</li>
						@endcan

						<li>
							<a href="{{ url('/') }}" target="_blank", data-icon="globe fa-lg text-info">
								{!! UI::hidden(Lang::get('cms::core.navigation.site')) !!}
							</a>
						</li>

						@event('view.navbar.right.after')

					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
