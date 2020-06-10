<div id="rule-description-settings" class="toggleable-settings hide-settings card-footer">

    <h3 class="h5 mb-3">Rule description: <span class="font-weight-normal">{{ $rules->rule_description }}</span></h3>

    <label for="rule-description">Rule definition</label>
    <div class="copiable-text-area">
        <textarea id="rule-description" name="content" class="form-control text-prewrap" disabled="disabled">
            {{ json_encode(json_decode($rules->rule_json),JSON_PRETTY_PRINT) }}
        </textarea>
        <button class="btn btn-sm btn-outline-primary copy-button" title="Copy to clipboard"><i class="fas fa-copy"></i></button>
    </div>
</div>
@include('partials.settings_toggler', ['label' => 'Rules', 'target' => '#rule-description-settings'])
