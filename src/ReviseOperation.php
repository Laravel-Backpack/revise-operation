<?php

namespace Backpack\ReviseOperation;

use Illuminate\Support\Facades\Route;
use Venturecraft\Revisionable\Revision;

trait ReviseOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param  string $segment       Name of the current entity (singular). Used as first URL segment.
     * @param  string $routeName    Prefix of the route name.
     * @param  string $controller Name of the current CrudController.
     */
    protected function setupReviseRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/revise', [
            'as' => $routeName.'.listRevisions',
            'uses' => $controller.'@listRevisions',
            'operation' => 'revise',
        ]);

        Route::post($segment.'/{id}/revise/{revisionId}/restore', [
            'as' => $routeName.'.restoreRevision',
            'uses' => $controller.'@restoreRevision',
            'operation' => 'revise',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupReviseDefaults()
    {
        // allow access to the operation
        $this->crud->allowAccess('revise');

        $this->crud->operation('revise', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation(['list', 'show'], function () {
            // add a button in the line stack
            $this->crud->addButton('line', 'revise', 'view', 'revise-operation::revise_button', 'end');
        });

        // add a new method on the CrudPanel object to allow this operation
        // to call getRevisionsForEntry from multiple operation methods
        $this->crud->macro('getRevisionsForEntry', function ($id) {
            $revisions = [];

            // Group revisions by change date
            foreach ($this->getEntry($id)->revisionHistory as $history) {
                // Get just the date from the revision created timestamp
                $revisionDate = date('Y-m-d', strtotime((string) $history->created_at));

                // Be sure to instantiate the initial grouping array
                if (! array_key_exists($revisionDate, $revisions)) {
                    $revisions[$revisionDate] = [];
                }

                // Push onto the top of the current group - so we get orderBy decending timestamp
                array_unshift($revisions[$revisionDate], $history);
            }

            // Sort the array by timestamp descending (so that the most recent are at the top)
            krsort($revisions);

            return $revisions;
        });
    }

    /**
     * Display the revisions for specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function listRevisions($id)
    {
        $this->crud->hasAccessOrFail('revise');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // calculate the revisions for that id
        $revisions = $this->crud->getRevisionsForEntry($id);

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name).' '.trans('revise-operation::revise.revisions');
        $this->data['id'] = $id;
        $this->data['revisions'] = $revisions;

        return view($this->crud->get('revise.listView') ?? 'revise-operation::revisions', $this->data);
    }

    /**
     * Restore a specific revision for the specified resource.
     *
     * Used via AJAX in the revisions view
     *
     * @param int $id
     *
     * @return JSON Response containing the new revision that was created from the update
     * @return HTTP 500 if the request did not contain the revision ID
     */
    public function restoreRevision($id)
    {
        $this->crud->hasAccessOrFail('revise');

        $revisionId = \Request::input('revision_id', false);
        if (! $revisionId) {
            abort(500, 'Can\'t restore revision without revision_id');
        } else {
            $entry = $this->crud->getEntryWithoutFakes($id);
            $revision = Revision::findOrFail($revisionId);

            // Update the revisioned field with the old value
            $entry->update([$revision->key => $revision->old_value]);

            $this->data['entry'] = $this->crud->getEntry($id);
            $this->data['crud'] = $this->crud;
            $this->data['revisions'] = $this->crud->getRevisionsForEntry($id); // Reload revisions as they have changed

            // Rebuild the revision timeline HTML and return it to the AJAX call
            return view($this->crud->get('revise.timelineView') ?? 'revise-operation::revision_timeline', $this->data);
        }
    }
}
