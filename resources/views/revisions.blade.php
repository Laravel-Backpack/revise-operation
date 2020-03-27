@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('revise-operation::revise.revisions') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;

  $heading = $crud->getHeading() ?? $crud->entity_name_plural;
  $subheading = $crud->getSubheading() ?? method_exists($entry, 'identifiableName') ? trans('revise-operation::revise.revisions_for').' "'.$entry->identifiableName().'"' : trans('revise-operation::revise.revisions');
@endphp

@section('header')
  <div class="container-fluid">
    <h2>
        <span class="text-capitalize">{!! $heading !!}</span>
        <small>{!! $subheading !!}.</small>

        @if ($crud->hasAccess('list'))
          <small><a href="{{ url($crud->route) }}" class="hidden-print font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
    </h2>
  </div>
@endsection

@section('content')
<div class="row m-t-20">
  <div class="{{ $crud->get('revise.timelineContentClass') ?? 'col-md-12' }}">
    <!-- Default box -->

    @if(!count($revisions))
      <div class="card">
        <div class="card-header with-border">
          <h3 class="card-title">{{ trans('revise-operation::revise.no_revisions') }}</h3>
        </div>
      </div>
    @else
      @include('revise-operation::revision_timeline')
    @endif
  </div>
</div>
@endsection


@section('after_styles')
  <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/revisions.css') }}">
@endsection

@section('after_scripts')
  <script src="{{ asset('packages/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('packages/backpack/crud/js/revisions.js') }}"></script>
@endsection
