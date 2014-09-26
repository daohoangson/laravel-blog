<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Entry extends Eloquent
{
	use SoftDeletingTrait;

	protected $table = 'entries';

	protected $guarded = array();

	public function user()
    {
        return $this->belongsTo('User');
    }
}
