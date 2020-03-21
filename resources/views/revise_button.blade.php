@if ($crud->hasAccess('revise') && count($entry->revisionHistory))
    <a href="{{ url($crud->route.'/'.$entry->getKey().'/revise') }}" class="btn btn-sm btn-link"><i class="la la-history"></i> {{ trans('revise-operation::revise.revisions') }}</a>
@endif
