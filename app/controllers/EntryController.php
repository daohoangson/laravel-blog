<?php

class EntryController extends BaseController
{
    const ENTRIES_PER_PAGE = 10;

    public function showList()
    {
        if (!Auth::guest() AND Auth::user()->isAdministrator()) {
            $entries = Entry::withTrashed()->orderBy('created_at', 'desc');
        } else {
            $entries = Entry::orderBy('created_at', 'desc');
        }

        $entries = $entries->paginate(self::ENTRIES_PER_PAGE);

        return View::make('entry.list', array('entries' => $entries));
    }

    public function showCreate()
    {
        if (!Auth::user()->canCreateEntry()) {
            App::abort(403);
        }

        return View::make('entry.edit', array('entry' => null));
    }

    public function showEdit($entry)
    {
        if (!Auth::user()->canEditEntry($entry)) {
            App::abort(403);
        }

        return View::make('entry.edit', array('entry' => $entry));
    }

    public function processSave()
    {
        $entryId = Input::get('id');
        $authUser = Auth::user();

        if ($entryId > 0) {
            $entry = Entry::find($entryId);
            if (empty($entry)) {
                App::abort(404);
            }

            if (!$authUser->canEditEntry($entry)) {
                App::abort(403);
            }
        } else {
            $entry = new Entry();

            if (!$authUser->canCreateEntry()) {
                App::abort(403);
            }
        }

        $delete = Input::get('delete');
        if (!empty($delete)) {
            $entry->delete();
            return Redirect::route('index');
        }

        $input = array(
            'title' => Input::get('title'),
            'body' => Input::get('body'),
        );

        $validator = Validator::make($input, array(
            'title' => array('required'),
            'body' => array('required'),
        ));

        if ($validator->fails()) {
            if (!empty($entry->id)) {
                $route = Redirect::route('entry_edit', $entry->id);
            } else {
                $route = Redirect::route('entry_create');
            }

            return $route->withInput()->withErrors($validator);
        }

        $entry->title = $input['title'];
        $entry->body = $input['body'];

        if (empty($entry->id)) {
            $authUser->entries()->save($entry);
        } else {
            $entry->save();
        }

        return Redirect::route('entry_view', $entry->id);
    }

    public function showDelete($id)
    {
        $entry = Entry::onlyTrashed()->where('id', '=', $id)->firstOrFail();

        if (!Auth::user()->canDeleteEntry($entry)) {
            App::abort(403);
        }

        return View::make('entry.delete', array('entry' => $entry));
    }

    public function processDelete($id)
    {
        $entry = Entry::onlyTrashed()->where('id', '=', $id)->firstOrFail();

        if (!Auth::user()->canDeleteEntry($entry)) {
            App::abort(403);
        }

        switch (Input::get('action'))
        {
            case 'hard_delete':
                $entry->forceDelete();
                $route = Redirect::route('index');
                break;
            case 'restore':
                $entry->restore();
                $route = Redirect::route('entry_view', $entry->id);
                break;
        }

        return $route;
    }

    public function showView(Entry $entry)
    {
        $entry->views++;
        $entry->save();

        return View::make('entry/view', array('entry' => $entry));
    }

}
