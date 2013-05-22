<?php
class Character extends Eloquent {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'characters';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('User');
    }
    public function items()
    {
        return $this->belongsToMany('Item', 'character_items', 'character_id', 'item_id');
    }
}
