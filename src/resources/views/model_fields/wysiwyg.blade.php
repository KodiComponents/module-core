<div {!! HTML::attributes($group->getAttributes()) !!}>
 	<h4>{!! $field->getTitle() !!}</h4>

	{!! $field->render() !!}
</div>

@push('scripts')
<script>$(function() {CMS.filters.switchOn('{{ $field->getId() }}', DEFAULT_HTML_EDITOR)})</script>
@endpush