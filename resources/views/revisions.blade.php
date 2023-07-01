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
  <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-end" bp-section="page-header">
    <h1 bp-section="page-heading" class="text-capitalize mb-2">
      {!! $heading !!}
    </h1>
    <p class="ms-2 ml-2 mb-2" bp-section="page-subheading">
      {!! $subheading !!}.
    </p>
    @if ($crud->hasAccess('list'))
    <p class="ms-2 ml-2 mb-2" bp-section="page-subheading-back-button">
      <small><a href="{{ url($crud->route) }}" class="hidden-print font-sm"><i class="la la-angle-double-left"></i> {{
          trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
    </p>
    @endif
  </section>
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
