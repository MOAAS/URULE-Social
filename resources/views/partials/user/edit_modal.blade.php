<div class="modal fade" id="edit-profile-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="edit-profile-form" class="modal-dialog modal-lg" data-edit-action="info">
        @csrf
        <input type="hidden" name="_method" value="PUT"/>

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="border-bottom position-relative banner-photo-container">
                    <label for='banner-input' class="image-input">
                        @include('partials.user.banner_photo', ['user' => $user])
                        <span class="image-input-overlay"></span>
                    </label>
                    <label for='picture-input' class="image-input">
                        @include('partials.user.avatar_large', ['user' => $user])
                        <span class="image-input-overlay"></span>
                    </label>
                </div>
                <div id="edit-info-form">    
                    <div class="row justify-content-sm-center justify-content-around my-3">
                        <button type="button" class="col-lg-3 col-5 btn btn-primary mx-sm-3 mx-1 to-edit-email-btn">Edit Email</button>
                        <button type="button" class="col-lg-3 col-5 btn btn-primary mx-sm-3 mx-1 to-edit-password-btn">Edit Password</button>
                    </div>

                    <input id="picture-input" type="file" accept="image/*" name="picture" class="d-none" />
                    <input id="banner-input" type="file" accept="image/*" name="banner" class="d-none" />

                    @include('partials.auth.form_input', ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'placeholder' => 'Your name', 'value' => $user->name])
                    @include('partials.auth.form_input', ['label' => 'Birth date', 'name' => 'birthday', 'type' => 'date', 'value' => $user->birthday])
                    @include('partials.auth.form_input', ['label' => 'Location', 'name' => 'location', 'type' => 'text', 'placeholder' => 'Where you currently live', 'value' => $user->location])
                </div>
                <div id="edit-email-form" class="d-none">
                    <div class="row justify-content-sm-center justify-content-around my-3">
                        <button type="button" class="col-lg-3 col-5 btn btn-primary mx-sm-3 mx-1 to-edit-info-btn">Edit Profile</button>
                        <button type="button" class="col-lg-3 col-5 btn btn-primary mx-sm-3 mx-1 to-edit-password-btn">Edit Password</button>
                    </div>    
                    @if(!$user->isGoogleAccount())
                        @include('partials.auth.form_input', ['label' => 'Current password', 'name' => 'curr_password_mail', 'type' => 'password', 'placeholder' => 'Your password'])
                        @include('partials.auth.form_input', ['label' => 'New email', 'name' => 'new_email', 'type' => 'email', 'placeholder' => 'Your new account email'])
                    @else
                        <h4 class="mt-4">Google accounts cannot change email.</h4>
                    @endif
                </div>
                <div id="edit-password-form" class="d-none">    
                    <div class="row justify-content-sm-center justify-content-around my-3">
                        <button type="button" class="col-lg-3 col-5 btn btn-primary mx-sm-3 mx-1 to-edit-email-btn">Edit Email</button>
                        <button type="button" class="col-lg-3 col-5 btn btn-primary mx-sm-3 mx-1 to-edit-info-btn">Edit Profile</button>
                    </div>
                    @if(!$user->isGoogleAccount())
                        @include('partials.auth.form_input', ['label' => 'Current password', 'name' => 'curr_password_pass', 'type' => 'password', 'placeholder' => 'Your password'])
                        @include('partials.auth.form_input', ['label' => 'New password', 'name' => 'new_password', 'type' => 'password', 'placeholder' => 'Your new account password'])
                        @include('partials.auth.form_input', ['label' => 'Confirm new password', 'name' => 'new_password_confirmation', 'type' => 'password', 'placeholder' => 'Your new account password'])
                    @else
                        <h4 class="mt-4">Google accounts cannot change password.</h4>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button id="delete-acc-btn" type="button" class="btn btn-danger mr-auto d-none">Delete Account</button>
                <button type="button" class="btn btn-secondary d-none d-sm-block" data-dismiss="modal">Close</button>
                <button id="edit-profile-btn" type="submit" class="btn btn-primary" data-is-google="{{$user->isGoogleAccount()}}">Save changes</button>
            </div>
        </div>
    </form>
</div>