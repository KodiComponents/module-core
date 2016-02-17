<div class="panel tabbable">
	<div class="panel-heading">
		<span class="panel-title">@lang('cms::system.tab.about.general')</span>
	</div>
	<table class="table table-striped">
		<colgroup>
			<col width="200px" />
			<col />
		</colgroup>
		<tbody>
		<tr>
			<th>@lang('cms::system.label.about.cms')</th>
			<td>{{ CMS::NAME }} v{{ CMS::VERSION }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.framework')</th>
			<td>{!! HTML::image('cms/images/laravel-logo.png', null, ['style' => 'height: 17px']) !!} <strong style="color: #E74430">Laravel</strong> v{{ App::version() }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.php_version')</th>
			<td>{{ PHP_VERSION }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.environment')</th>
			<td>{{ env('APP_ENV') }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.host')</th>
			<td>{{ array_get($_SERVER, 'HTTP_HOST') }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.server')</th>
			<td>{{ array_get($_SERVER, 'SERVER_SOFTWARE') }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.cache_driver')</th>
			<td>{{ env('CACHE_DRIVER') }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.session_driver')</th>
			<td>{{ env('SESSION_DRIVER') }}</td>
		</tr>
		</tbody>
	</table>

	<div class="panel-heading">
		<span class="panel-title">@lang('cms::system.tab.about.modules')</span>
	</div>
	<div class="panel-body">
		@foreach($modules as $module)
		<div class="panel">
			@if($module->getInfo())
			<div class="panel-heading">
				<h4>
					{{ array_get($module->getInfo(), 'package') }}  {{ array_get($module->getInfo(), 'version') }}
					<small>{{ $module->getName() }}</small>
				</h4>
			</div>
			<div class="panel-body">
				@if($description = array_get($module->getInfo(), 'description'))
					<p class="text-muted">{{ $description }}</p>
				@endif

				@if($authors = array_get($module->getInfo(), 'authors'))
					<hr />
					<h5>@lang('cms::system.module.authors')</h5>
					<ul>
						@foreach($authors as $author)
							<li>
								{{ array_get($author, 'name') }}

								@if($email = array_get($author, 'email'))
									{!! HTML::mailto($email) !!}
								@endif
							</li>
						@endforeach
					</ul>
				@endif

				@if($support = array_get($module->getInfo(), 'support'))
					<hr />
					<h5>@lang('cms::system.module.support')</h5>
					<ul>
						@foreach($support as $type => $link)
							<li>{!! link_to($link, $type, ['target' => '_blank']) !!}</li>
						@endforeach
					</ul>
				@endif
			</div>
			@else
			<div class="panel-heading">
				<h4>{{ $module->getName() }}</h4>
			</div>
			@endif
			<div class="panel-footer">
				<code>{{ $module->getNamespace() }} [{{ $module->getPath() }}]</code>
			</div>
		</div>
		@endforeach
	</div>

	@if (acl_check('system.phpinfo') and function_exists('phpinfo'))
	<div class="panel-heading">
		<span class="panel-title">@lang('cms::system.tab.about.php_info')</span>
	</div>
	<div class="panel-body no-padding">
		<iframe src="{{ route('backend.phpinfo') }}" width="100%" height="500px" id="phpinfo" style="border: 0"></iframe>
	</div>
	@endif

	@event('view.system.about')
</div>