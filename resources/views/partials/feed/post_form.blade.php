<div class="card shadow-sm mb-4">
    <div class="card-title bg-light p-2 m-0">
        @include('partials.user.avatar', ['user' => $user])
    </div>

    <form id="post-form" action="{{ route('post.create') }}" method="post">
        @csrf

        <textarea id="post-input" class="form-control auto-resizable-textarea" name="content" placeholder="Write your post here..." rows="5"></textarea>

        <div id="post-settings" class="toggleable-settings hide-settings">
            <div class="form-group">
                <label for="post-rules">Rules <span class="clickable text-primary" data-toggle="modal" data-target="#rule-help">(What's this?)</span></label>

                @include('partials.feed.rule_help.modal')

                <div class="d-flex align-items-start">
                    <div>
                        <input id="post-rules" name="rule" type="text" placeholder="Just to spice things up." class="form-control w-auto">
                    </div>

                    <label for="rule-file" class="ml-2 btn btn-outline-primary rounded clickable">
                       <i class="fa fa-plus"></i>
                    </label>
                    <input id="rule-file" type="file" accept=".json,.txt" class="d-none"/>
                </div>

                <label id="post-image-area" class="clickable my-4" for="post-image-input">
                    <span>UPLOAD IMAGE</span>
                    <i class="fas fa-times-circle bg-white rounded-circle h-3"></i>
                    <img id="post-image-preview" src="#" alt="" class="img-fluid">
                </label>
                <input id="post-image-input" type="file" accept="image/*" name="image" class="d-none"/>
            </div>

            <div class="border-bottom my-4"></div>

            <div class="form-group form-check">
                <input id="private-post" name="private-post" type="checkbox" class="form-check-input">
                <label class="form-check-label" for="private-post">Private post</label>
            </div>
        </div>

        @include('partials.settings_toggler', ['label' => 'Settings', 'target' => '#post-settings'])

        <button id="post-btn" type="submit" class="ml-auto btn btn-primary">Post</button>
    </form>
</div>
