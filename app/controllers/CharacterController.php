<?php

class CharacterController extends BaseController {
    public $characters;
    /**
     * Attach the Auth filter to the controller
     *
     */
    public function __construct()
    {
        $this->beforeFilter('auth');
        $this->characters = User::find( (int)Sentry::getUser()->id )->characters;

    }

    /**
     * Show the character profile
     *
     * @param  int $id
     * @return View
     */
    public function getProfile( $id )
    {
        // Get a character with all its items (& item modifiers)
        $items = Character::whereId($id)->with('items.attributes')->first()->toArray();

        $heroStats = Diablo3Util::saveCharacterStatistics( $id );



        // Gets all the items + adds the type & uniqueness to it
        $itemSet = Diablo3Util::getItemSet( $items );

        // Downloads the skill images
        // TODO: Execute this at hero import, so it happens only once.
        Diablo3Util::getSkillImages( $items['hero_id'] );

        // Get skillSet for current user
        $skillSet = Diablo3Util::getSkillset( $items['hero_id'] );

        $data = [
            'user'       => Sentry::getUser(),
            'characters' => $this->characters,
            'items'      => $itemSet,
            'skills'     => $skillSet,
            'heroStats'  => $heroStats
        ];
        return View::make( 'user.character', $data );
    }
}
