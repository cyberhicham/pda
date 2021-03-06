<?php

class AccountController extends BaseController {
    /**
     * Attach the Auth filter to the controller
     *
     */
    public function __construct()
    {
        $this->beforeFilter('auth');
    }


    /**
     * Displaying the dashboard
     * If no characters are already imported, prompt the user to import
     * If proile not completed, ask for completion
     *
     */
    public function getIndex()
    {
        // Look for characters for logged in user
        $user = User::find( (int)Sentry::getUser()->id )->characters->first();

        // If the user has characters
        if ( $user )
        {
            $data = [
                'characters' => User::find( (int)Sentry::getUser()->id )->characters,
                'heroes' => User::getCharactersToImport(),
                'user' => Sentry::getUser()
            ];
            return View::make('user.index', $data);
        }
        else
        {
            $data = [
                'notice' => "You havent imported any characters yet. <a data-toggle='modal' href='#modal' >Do it now!</a>",
                'heroes' => User::getCharactersToImport(),
                'user' => Sentry::getUser(),
            ];
            return View::make('user.index', $data);
        }
    }

    /**
     * Updating user info
     * First Name & Last Name
     *
     * @return Reponse::json
     */
    public function postUpdateUserInfo()
    {
        $firstName = Input::get('firstName');
        $lastName = Input::get('lastName');

        $validator = new Services\Validators\User;
        if ( $validator->passes() )
        {
            try
            {
                // Find the user using the user id
                $user = Sentry::getUserProvider()->findById( Sentry::getUser()->id );

                // Update the user details
                $user->first_name = $firstName;
                $user->last_name = $lastName;

                // Update the user
                if ($user->save())
                {
                    $data = [ 'success' => 'Profile updated.' ];
                    return Response::json( $data );
                }
            }
            catch (Cartalyst\Sentry\Users\UserExistsException $e)
            {
                $data = [ 'error' => 'User with this login already exists.' ];
                return Response::json( $data );
            }
            catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
            {
                $data = [ 'error' => 'User was not found.' ];
                return Response::json( $data );
            }
        }
        else
        {
            $errors = $validator->getErrors();
            $data = [
                'error' => 'Something went wrong',
                'errors' => $errors->all(),
            ];
            return Response::json( $data );
        }
    }

    /**
     * Updating user info
     * Battletag & Server
     *
     * @return Reponse::json
     */
    public function postUpdateUserBtagInfo()
    {
        $battletag = Input::get('battletag');
        $server = Input::get('server');

        $validator = new Services\Validators\Battlenet;

        if ( $validator->passes() )
        {
            try
            {
                // Find the user using the user id
                $user = Sentry::getUserProvider()->findById( Sentry::getUser()->id );

                // Update the user details
                $user->battletag = $battletag;
                $user->server = $server;

                // Update the user
                if ($user->save())
                {
                    $data = [ 'success' => 'Profile updated.' ];
                    return Response::json( $data );
                }
            }
            catch (Cartalyst\Sentry\Users\UserExistsException $e)
            {
                $data = [ 'error' => 'User with this login already exists.' ];
                return Response::json( $data );
            }
            catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
            {
                $data = [ 'error' => 'User was not found.' ];
                return Response::json( $data );
            }
        }
        else
        {
            $errors = $validator->getErrors();
            $errorBattletag = ($errors->has('battletag')) ? $errors->get('battletag') : null;
            $data = [
                'error' => 'Something went wrong',
                'errors' => $errors->all(),
            ];
            return Response::json( $data );
        }
    }

    public function postChangeUserPassword()
    {
        // Declare the rules for the form validation
        $rules = array(
            'oldPassword'     => 'required|between:3,32',
            'password'         => 'required|between:3,32',
            'passwordConfirm' => 'required|same:password',
        );

        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails())
        {
            // Ooops.. something went wrong
            $errors = $validator->messages();
            $data = [
                'error' => 'Something went wrong',
                'errors' => $errors->all(),
            ];
            return Response::json( $data );
        }

        // Grab the user
        $user = Sentry::getUser();

        // Check the user current password
        if ( ! $user->checkPassword(Input::get('oldPassword')))
        {
            // Set the error message
            $data = [ 'error' => 'Your current password is incorrect.' ];

            // Redirect to the change password page
            return Response::json( $data );
        }

        // Update the user password
        $user->password = Input::get('password');
        $user->save();

        // Redirect to the change-password page
        $data = [ 'success' => 'Password changed.' ];
        return Response::json( $data );
    }
}
