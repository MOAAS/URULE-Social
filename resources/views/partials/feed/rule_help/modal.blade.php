<div class="modal fade" id="rule-help" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">The ultimate guide to rule making</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="rule-help-body" class="modal-body p-0">
                @include('partials.feed.rule_help.option', ['content' => "What are rules?", 'id' => 'rule-help-1'])
                <div id="rule-help-1" class="collapse" data-parent="#rule-help-body">
                    <div class="p-3">
                        <p>Rules are designed to make our social network a little more exciting!</p>
                        <p>When you make a post, you can choose some restrictions the comments will have to obey. Sounds fun, right?</p>
                    </div>
                </div>

                @include('partials.feed.rule_help.option', ['content' => "How do I make a Rule?", 'id' => 'rule-help-2'])
                <div id="rule-help-2" class="collapse" data-parent="#rule-help-body">
                    <div class="p-3">
                        <p>Rules are built with a syntax called <a href="https://en.wikipedia.org/wiki/JSON">JSON</a>. It was designed to be easily read by both humans and machines. You don't really need to understand the whole concept, but here's how a Rule in JSON would look like</p>
<pre>
{
 "description": "Everyone must greet and say goodbye.",
 "startsWith": "Hello everyone!",
 "endsWith": "Goodbye everyone!"
}
</pre>
                        <p>As you can imagine, this rule would only allow comments that start with the expression "Hello everyone!" and ending with "Goodbye everyone!" It will also contain a neat little description for the users to have an idea of what this rule is about.</p>
                        <p>Paste that on the 'rules' box and you'll be good to go! If you want, you can also use a text editor (like notepad), and submit it using the + icon.</p>
                    </div>
                </div>

                @include('partials.feed.rule_help.option', ['content' => "Where can I find a list of my options?", 'id' => 'rule-help-3'])
                <div id="rule-help-3" class="collapse" data-parent="#rule-help-body">
                    <div class="p-3">
                        <p>Right here! We plan to introduce a lot more customizabilty to our rules, but for now, these are the attributes we have. Remember they must be enclosed with quotation marks ("like this")</p>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Attribute</th>
                                    <th scope="col" class="d-md-block d-none">Type</th>
                                    <th scope="col" >Functionality</th>
                                    <th scope="col" class="d-md-block d-none">Restrictions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('partials.feed.rule_help.attributes')
                            </tbody>
                        </table>
                    </div>
                </div>

                @include('partials.feed.rule_help.option', ['content' => "What are all these types?", 'id' => 'rule-help-4'])
                <div id="rule-help-4" class="collapse" data-parent="#rule-help-body">
                    <div class="p-3">
                        <p>In the previous section, we described all the available rule attributes, but we also introduced something called "Types". If you have no idea what they mean, here's a description.</p>

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">String</th>
                                    <td>Basically, a piece of text. Anything you write could be a string. In JSON, you must surround them with quotation marks ("like this!").</td>
                                </tr>
                                <tr>
                                    <th scope="row">Integer</th>
                                    <td>You probably know this one. Any non-decimal number will work. Do not use quotation marks. </td>
                                </tr>
                                <tr>
                                    <th scope="row">Boolean</th>
                                    <td>Represents a state of truth. Can either be true and false. Do not use quotation marks.</td>
                                </tr>
                                <tr>
                                    <th scope="row">Object</th>
                                    <td>A list of key-value pairs. Your entire rule is a JSON Object, and a value could also be a JSON Object. It is surrounded by curly braces { }. /td>
                                </tr>
                                <tr>
                                    <th scope="row">Array</th>
                                    <td>A list of anything. In JSON, you use square brackets [], like this: ["element1", "element2"].</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                @include('partials.feed.rule_help.option', ['content' => "It says here my rule is invalid. Help me!", 'id' => 'rule-help-5'])
                <div id="rule-help-5" class="collapse" data-parent="#rule-help-body">
                    <div class="p-3">
                        <p>Usually, the error message should be enough to figure out what's wrong exactly. However, if the error says 'Invalid JSON', check <a href="https://jsonlint.com/">this website</a> in order to get a more detailed description of the error.</p>
                        <p>Make sure that every attribute (the words on the left) is enclosed with quotation marks ("like this"), as well as about everything that's not a number.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>