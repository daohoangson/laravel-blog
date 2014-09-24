<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Entry extends Eloquent
{
	use SoftDeletingTrait;

	protected $table = 'entries';

	public function user()
    {
        return $this->belongsTo('User');
    }
}
