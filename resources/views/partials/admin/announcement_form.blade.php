<div class="card shadow-sm mb-4">
    <div class="card-title bg-light p-2 m-0">
        @include('partials.user.avatar', ['user' => $user])
    </div>
        <form id="announcement-form" method="post" action="{{ route('announcement.create') }}">
            @csrf
            <textarea id="announcement-input" class="form-control" name="content" placeholder="Make an announcement" rows="5"></textarea>
            <label for="announcement-duration-input" class="m-0 mx-2">Duration:</label>
            <div id="announcement-settings"  class="form-row align-items-center px-2">
                <div class="col-md-1 col-3">
                    <input id="announcement-duration-input" type="number" class="form-control" name="duration_num" value="1" min="1" max="999">
                </div>
                <div class="col-md-2 col-6">
                    <select name="duration_unit" class="form-control">
                        <option>Hours</option>
                        <option>Days</option>
                        <option>Weeks</option>
                        <option>Months</option>
                    </select>
                </div>
                <button id="announcement-btn" type="submit" class="ml-auto m-2 btn btn-primary">Post</button>
            </div>
        </form>
</div>