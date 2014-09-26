<?php

class EntryResourceManager extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (!Auth::guest() AND Auth::user()->isAdministrator()) {
            $entries = Entry::withTrashed()->orderBy('created_at', 'desc');
        } else {
            $entries = Entry::orderBy('created_at', 'desc');
        }

        $entries = $entries->paginate(EntryController::ENTRIES_PER_PAGE)->toArray();
        $entries['data'] = $this->_prepareEntries($entries['data']);

        return Response::json($entries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (!Auth::users()->canCreateEntry()) {
            App::abort(403);
        }

        $input = array(
            'title' => Input::get('title'),
            'body' => Input::get('body'),
        );

        $validator = Validator::make($input, array(
            'title' => array('required'),
            'body' => array('required'),
        ));

        $entry = new Entry();
        $entry->title = $input['title'];
        $entry->body = $title['body'];

        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->messages()));
        }

        $entry->save();

        return Response::json(array('entry' => $entry));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $entry = Entry::find($id);
        if (empty($entry)) {
            App::abort(404);
        }

        if (!Auth::users()->canEditEntry($entry)) {
            App::abort(403);
        }

        $input = array(
            'title' => Input::get('title'),
            'body' => Input::get('body'),
        );

        $validator = Validator::make($input, array(
            'title' => array('required'),
            'body' => array('required'),
        ));

        $entry->title = $input['title'];
        $entry->body = $title['body'];

        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->messages()));
        }

        $entry->save();

        return Response::json(array('entry' => $entry));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $entry = Entry::find($id);
        if (empty($entry)) {
            App::abort(404);
        }

        if (!Auth::users()->canEditEntry($entry)) {
            App::abort(403);
        }

        $entry->delete();

        return Response::json(array('success' => true));
    }

    protected function _prepareEntries(array $entries)
    {
        foreach ($entries as &$entryRef) {
            $entryRef = $this->_prepareEntry($entryRef);
        }

        return $entries;
    }

    protected function _prepareEntry(array $entry)
    {
        if (Auth::guest()) {
            $entry['canEditEntry'] = false;
            $entry['canDeleteEntry'] = false;
        } else {
            $user = Auth::user();
            $entryObj = new Entry($entry);

            $entry['canEditEntry'] = $user->canEditEntry($entryObj);
            $entry['canDeleteEntry'] = $user->canDeleteEntry($entryObj);
        }

        return $entry;
    }

}
