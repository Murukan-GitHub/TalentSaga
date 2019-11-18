@extends('backend.layouts.base')

@section('content')
   <main class="main" id="main">
        <div class="container">
            <section class="block">
                <h1>Typography</h1>
                <hr>
                <h1 class="heading">Suitcore Heading 1</h1>
                <h2 class="heading">Suitcore Heading 2</h2>
                <h3 class="heading">Suitcore Heading 3</h3>
                <h4 class="heading">Suitcore Heading 4</h4>
                <h5 class="heading">Suitcore Heading 5</h5>
                <h6 class="heading">Suitcore Heading 6</h6>

                <p><a href="#">Link</a> and normal text</p>
                <p><strong>Bold text</strong></p>
                <p><em>Italic text</em></p>
                <p><strong><em>Bold italic text</em></strong></p>
                <p class="text-muted">Muted text</p>
                <p class="text-ellipsis">Truncated text. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate eius quaerat aliquid quae quia perferendis tenetur dolore sed, architecto, asperiores! Aut molestias, officia et nemo voluptate aliquam quo repellat at!</p>
            </section>
            <br><br>

            <section class="block">
                <h1>Icons</h1>
                <hr>

                <h2>Usage</h2>

                <pre>
&lt;span class="fa fa-fw fa-[ICON_NAME]">&lt;/span></pre>

                <p><a href="https://fortawesome.github.io/Font-Awesome/icons/">Check icon name and available icons here.</a></p>
            </section>
            <br><br>

            <section class="block">
                <h1>Buttons</h1>
                <hr>

                <h2>Small buttons</h2>
                <p>
                    <button class="btn btn--sm btn--red">Button red</button>
                    <button class="btn btn--sm btn--blue">Button blue</button>
                    <button class="btn btn--sm btn--navy">Button navy</button>
                    <button class="btn btn--sm btn--gray">Button gray</button>
                    <button class="btn btn--sm btn--lime">Button lime</button>
                    <button class="btn btn--sm btn--plain">Button plain</button>
                </p>

                <h2>Normal buttons</h2>
                <p>
                    <button class="btn btn--red">Button red</button>
                    <button class="btn btn--blue">Button blue</button>
                    <button class="btn btn--navy">Button navy</button>
                    <button class="btn btn--gray">Button gray</button>
                    <button class="btn btn--lime">Button lime</button>
                    <button class="btn btn--plain">Button plain</button>
                </p>
<pre>
&lt;button class="btn btn--[red|blue|navy|gray|lime]">Button red&lt;/button>
</pre>

                <h2>Large buttons</h2>
                <p>
                    <button class="btn btn--lg btn--red">Button red</button>
                    <button class="btn btn--lg btn--blue">Button blue</button>
                    <button class="btn btn--lg btn--navy">Button navy</button>
                    <button class="btn btn--lg btn--gray">Button gray</button>
                    <button class="btn btn--lg btn--lime">Button lime</button>
                    <button class="btn btn--lg btn--plain">Button plain</button>
                </p>

                <h2>Disabled buttons</h2>
                <p>
                    <button class="btn btn--red" disabled>Button red</button>
                    <button class="btn btn--blue" disabled>Button blue</button>
                    <button class="btn btn--navy" disabled>Button navy</button>
                    <button class="btn btn--gray" disabled>Button gray</button>
                    <button class="btn btn--lime" disabled>Button lime</button>
                    <button class="btn btn--plain" disabled>Button plain</button>
                </p>

                <h2>Anchor buttons</h2>
                <p>
                    <a class="btn btn--red" href="#">Anchor red</a>
                    <a class="btn btn--blue" href="#">Anchor blue</a>
                    <a class="btn btn--navy" href="#">Anchor navy</a>
                    <a class="btn btn--gray" href="#">Anchor gray</a>
                    <a class="btn btn--lime" href="#">Anchor lime</a>
                    <a class="btn btn--plain" href="#">Anchor plain</a>
                </p>

                <h2>Buttons with icon</h2>
                <p>
                    <a class="btn btn--red" href="#">
                        <span class="fa fa-fw fa-plus"></span>
                        Add
                    </a>
                    <a class="btn btn--gray" href="#">
                        <span class="fa fa-fw fa-edit"></span>
                        Edit
                    </a>
                    <a class="btn btn--blue" href="#">
                        <span class="fa fa-fw fa-star"></span>
                        Favorite
                    </a>
                </p>
<pre>
&lt;a class="btn btn--red" href="#">
&lt;span class="fa fa-fw fa-plus">&lt;/span>
Add
&lt;/a>
</pre>
            </section>
            <br><br>

            <section class="block">
                <h1>Label</h1>
                <hr>

                <div class="block">
                    <span class="label label--red">Label red</span>
                    <span class="label label--blue">Label blue</span>
                    <span class="label label--navy">Label navy</span>
                    <span class="label label--gray">Label gray</span>
                    <span class="label label--lime">Label lime</span>
                </div>

<pre>&lt;span class="label label--red">Label red&lt;/span>
&lt;span class="label label--blue">Label blue&lt;/span>
&lt;span class="label label--navy">Label navy&lt;/span>
&lt;span class="label label--gray">Label gray&lt;/span>
&lt;span class="label label--lime">Label lime&lt;/span>
</pre>
            </section>
            <br><br>

            <section class="block">
                <h1>Breadcrumb</h1>
                <hr>

                <ul class="breadcrumb">
                    <li><a class="breadcrumb-anchor" href="#">Home</a></li>
                    <li><a class="breadcrumb-anchor" href="#">Level 1</a></li>
                    <li>Level 2</li>
                </ul>
<pre>
&lt;ul class="breadcrumb">
&lt;li>&lt;a class="breadcrumb-anchor" href="#">Home&lt;/a>&lt;/li>
&lt;li>&lt;a class="breadcrumb-anchor" href="#">Level 1&lt;/a>&lt;/li>
&lt;li>Level 2&lt;/li>
&lt;/ul>
</pre>
            </section>
            <br><br>

            <section class="block">
                <h1>Forms</h1>
                <hr>

                <div class="block">
                    <label for="">Input type text</label>
                    <input class="form-input" type="text">
                </div>

                <div class="block">
                    <label for="">Input type number</label>
                    <input class="form-input" type="number">
                </div>

                <div class="block">
                    <label for="">Input type date</label>
                    <input class="form-input" type="date">
                </div>

                <div class="block">
                    <label for="">Input type time</label>
                    <input class="form-input" type="time">
                </div>

                <div class="block">
                    <label for="">Input type datetime</label>
                    <input class="form-input" type="text" data-datetime-input>
                </div>

                <div class="block">
                    <label for="">Input type file</label>
                    <div class="bzg">
                        <div class="bzg_c" data-col="l10">
                            <input class="form-input" id="inputType" type="file">
                        </div>
                        <div class="bzg_c" data-col="l2">
                            <label class="btn btn--block btn--gray btn--form-input" for="inputType">Upload</label>
                        </div>
                    </div>
                </div>

                <div class="block">
                    <label for="">Textarea</label>
                    <textarea class="form-input" rows="3"></textarea>
                </div>

                <div class="block">
                    <label class="label-checkbox" for="">
                        <input type="checkbox" name="" id="">
                        <span>Input type checkbox</span>
                    </label>
                </div>

                <div class="block">
                    <label class="label-radio" for="">
                        <input type="radio" name="" id="">
                        <span>Input type radio</span>
                    </label>
                </div>

                <div class="block">
                    <label for="">Select</label>
                    <select class="form-input">
                        <option>Option 1</option>
                        <option>Option 2</option>
                        <option>Option 3</option>
                        <option>Option 4</option>
                        <option>Option 5</option>
                    </select>
                </div>

                <h2>States</h2>

                <div class="block">
                    <label for="">Error state</label>
                    <input class="form-input form-input--error" type="text">
                </div>

                <div class="block">
                    <label for="">Success state</label>
                    <input class="form-input form-input--success" type="text">
                </div>

                <div class="block">
                    <label for="">Disabled</label>
                    <input class="form-input" type="text" disabled>
                </div>

                <div class="block">
                    <label for="">Readonly</label>
                    <input class="form-input" type="text" value="John Hancock" readonly>
                </div>

                <h2>Autocomplete</h2>

                <div class="block">
                    <span>Search for "autocomplete"</span>
                    <input class="form-input" type="text" data-autocomplete="dev/autocomplete.json">
                </div>

<pre>&lt;input class="form-input" type="text" data-autocomplete="dev/autocomplete.json">
</pre>
                
                <h2>Textarea autosize</h2>

                <div class="block">
                    <textarea rows="3" class="form-input" data-autosize></textarea>
                </div>

<pre>&lt;textarea rows="3" data-autosize>&lt;/textarea>
</pre>
            </section>
            <br><br>

            <section class="block">
                <h1>Pagination</h1>
                <hr>

                <ul class="pagination">
                    <li><a href="#">Prev</a></li>
                    <li><a class="active" href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#">Next</a></li>
                </ul>

                <ul class="pagination text-center">
                    <li><a href="#">Prev</a></li>
                    <li><a class="active" href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#">Next</a></li>
                </ul>

                <ul class="pagination text-right">
                    <li><a href="#">Prev</a></li>
                    <li><a class="active" href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#">Next</a></li>
                </ul>
<pre>
&lt;ul class="pagination text-[left|center|right]">
&lt;li>&lt;a href="#">Prev&lt;/a>&lt;/li>
&lt;li>&lt;a class="active" href="#">1&lt;/a>&lt;/li>
&lt;li>&lt;a href="#">2&lt;/a>&lt;/li>
&lt;li>&lt;a href="#">3&lt;/a>&lt;/li>
&lt;li>&lt;a href="#">4&lt;/a>&lt;/li>
&lt;li>&lt;a href="#">5&lt;/a>&lt;/li>
&lt;li>&lt;a href="#">Next&lt;/a>&lt;/li>
&lt;/ul>
</pre>
            </section>
            <br><br>

            <section class="block">
                <h1>Table</h1>
                <hr>

                <h2>Basic table</h2>
                <table class="table table--zebra">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Occupation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John</td>
                            <td>12</td>
                            <td>Male</td>
                            <td>Student</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane</td>
                            <td>28</td>
                            <td>Female</td>
                            <td>Dancer</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Joe</td>
                            <td>25</td>
                            <td>Male</td>
                            <td>Striper</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Roland</td>
                            <td>24</td>
                            <td>Male</td>
                            <td>Food Blogger</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Wico</td>
                            <td>19</td>
                            <td>Female</td>
                            <td>Housewife</td>
                        </tr>
                    </tbody>
                </table>
<pre>
&lt;table class="table table--zebra">
&lt;thead>
    &lt;tr>
        &lt;th>...&lt;/th>
    &lt;/tr>
&lt;/thead>
&lt;tbody>
    &lt;tr>
        &lt;td>...&lt;/td>
    &lt;/tr>
&lt;/tbody>
&lt;/table>
</pre>

                <h2>Enhanced table (using <a href="http://datatables.net/">DataTable</a>)</h2>
                <table class="table table--zebra" data-enhance-table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Occupation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John</td>
                            <td>12</td>
                            <td>Male</td>
                            <td>Student</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane</td>
                            <td>28</td>
                            <td>Female</td>
                            <td>Dancer</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Joe</td>
                            <td>25</td>
                            <td>Male</td>
                            <td>Striper</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Roland</td>
                            <td>24</td>
                            <td>Male</td>
                            <td>Food Blogger</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Wico</td>
                            <td>19</td>
                            <td>Female</td>
                            <td>Housewife</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>John</td>
                            <td>12</td>
                            <td>Male</td>
                            <td>Student</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane</td>
                            <td>28</td>
                            <td>Female</td>
                            <td>Dancer</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Joe</td>
                            <td>25</td>
                            <td>Male</td>
                            <td>Striper</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Roland</td>
                            <td>24</td>
                            <td>Male</td>
                            <td>Food Blogger</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Wico</td>
                            <td>19</td>
                            <td>Female</td>
                            <td>Housewife</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>John</td>
                            <td>12</td>
                            <td>Male</td>
                            <td>Student</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane</td>
                            <td>28</td>
                            <td>Female</td>
                            <td>Dancer</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Joe</td>
                            <td>25</td>
                            <td>Male</td>
                            <td>Striper</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Roland</td>
                            <td>24</td>
                            <td>Male</td>
                            <td>Food Blogger</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Wico</td>
                            <td>19</td>
                            <td>Female</td>
                            <td>Housewife</td>
                        </tr>
                    </tbody>
                </table>
<pre>
&lt;table class="table table--zebra" data-enhance-table>
&lt;thead>
    &lt;tr>
        &lt;th>...&lt;/th>
    &lt;/tr>
&lt;/thead>
&lt;tbody>
    &lt;tr>
        &lt;td>...&lt;/td>
    &lt;/tr>
&lt;/tbody>
&lt;/table>
</pre>
                
                <h3>Load data via ajax on page load</h3>
                <table class="table table--zebra" data-enhance-table data-table-source="dev/data-table/asp.json">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Field 1</th>
                            <th>Field 2</th>
                        </tr>
                    </thead>
                </table>

<pre>&lt;table class="table table--zebra" data-enhance-table data-table-source="data.json">
&lt;thead>
    &lt;tr>
        &lt;th>#&lt;/th>
        &lt;th>Field 1&lt;/th>
        &lt;th>Field 2&lt;/th>
    &lt;/tr>
&lt;/thead>
&lt;/table>
</pre>

                <h3>Disable length change</h3>
                <p>Tambahkan attribute <code>data-length-change="false"</code></p>

                <table
                    class="table table--zebra"
                    data-enhance-table
                    data-table-source="dev/data-table/asp.json"
                    data-length-change="false">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Field 1</th>
                            <th>Field 2</th>
                        </tr>
                    </thead>
                </table>

<pre>&lt;table
class="table table--zebra"
data-enhance-table
data-table-source="dev/data-table/asp.json"
data-length-change="false">
&lt;thead>
    &lt;tr>
        &lt;th>#&lt;/th>
        &lt;th>Field 1&lt;/th>
        &lt;th>Field 2&lt;/th>
    &lt;/tr>
&lt;/thead>
&lt;/table>
</pre>
            </section>
            <br><br>

            <section class="block">
                <h1>Chart</h1>
                <hr>

                <h2>Bar Chart</h2>
                <div class="chart" data-chart="bar" data-value="dev/chart-data/chart-bar.json"></div>
                <p>Usage</p>
                <pre>&lt;div class="chart" data-chart="bar" data-value="dev/chart-data/chart-bar.json">&lt;/div></pre>

                <h2>Pie Chart</h2>
                <div class="chart" data-chart="pie" data-value="dev/chart-data/chart-pie.json"></div>
                <pre>&lt;div class="chart" data-chart="pie" data-value="dev/chart-data/chart-pie.json">&lt;/div></pre>

                <div class="chart" data-chart="pie" data-value="dev/chart-data/chart-pie.json" data-layout="vertical"></div>
                <pre>&lt;div class="chart" data-chart="pie" data-value="dev/chart-data/chart-pie.json" data-layout="vertical">&lt;/div></pre>

                <h2>Line Chart</h2>
                <div class="chart" data-chart="line" data-value="dev/chart-data/chart-line.json"></div>
                <pre>&lt;div class="chart" data-chart="line" data-value="dev/chart-data/chart-line.json">&lt;/div></pre>

                <h2>Bar Line Chart</h2>
                <div class="chart" data-chart="barLine" data-value="dev/chart-data/chart-bar-line.json"></div>
                <pre>&lt;div class="chart" data-chart="barLine" data-value="dev/chart-data/chart-bar-line.json">&lt;/div></pre>
            </section>
            <br><br>

            <section class="block">
                <h1>Progress</h1>
                <hr>

                <h2>Circular progress</h2>
                <div class="circular-progress" data-percent="95" data-info="On progress"></div>

<pre>&lt;div class="circular-progress" data-percent="95" data-info="On progress">&lt;/div>
</pre>

                <h2>Progressbar</h2>
                <div class="block">
                    <div class="progress progress--blue" data-progress="30">
                        <div class="progress__bar"></div>
                    </div>
                </div>

                <div class="block">
                    <div class="progress progress--green" data-progress="60">
                        <div class="progress__bar"></div>
                    </div>
                </div>

                <div class="block">
                    <div class="progress progress--gray" data-progress="90">
                        <div class="progress__bar"></div>
                    </div>
                </div>

<pre>
&lt;div class="progress progress--[blue|green|gray]" data-progress="30">
&lt;div class="progress__bar">&lt;/div>
&lt;/div>
</pre>

                <div class="block">
                    <div class="progress-group">
                        <div class="progress progress--blue" data-progress="60" data-label="Actual">
                            <div class="progress__bar"></div>
                        </div>
                        <div class="progress progress--gray" data-progress="55" data-label="Planning">
                            <div class="progress__bar"></div>
                        </div>
                    </div>
                </div>

<pre>
&lt;div class="progress-group">
&lt;div class="progress progress--blue" data-progress="60" data-label="Actual">
    &lt;div class="progress__bar">&lt;/div>
&lt;/div>
&lt;div class="progress progress--gray" data-progress="55" data-label="Planning">
    &lt;div class="progress__bar">&lt;/div>
&lt;/div>
&lt;/div>
</pre>
            </section>
            <br><br>

            <section class="block">
                <h1>Tab</h1>
                <hr>
                
                <h2>Default tab</h2>
                <div class="tabby">
                    <div class="tabby-triggers">
                        <a class="tabby-trigger active" href="#tab1">Tab 1</a>
                        <a class="tabby-trigger" href="#tab2">Tab 2</a>
                    </div>
                    <div class="tabby-tabs">
                        <div class="tabby-tab active" id="tab1">
                            <div class="box">
                                <p>Content tab 1</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorum provident sint, quos nobis voluptatibus doloremque, omnis, obcaecati harum soluta aperiam accusantium. Laboriosam aliquid, aut minima cumque reiciendis, deserunt nostrum vero!</p>
                            </div>
                        </div>
                        <div class="tabby-tab" id="tab2">
                            <div class="box">
                                Content tab 2
                            </div>
                        </div>
                    </div>
                </div>

<pre>
&lt;div class="tabby">
&lt;div class="tabby-triggers">
    &lt;a class="tabby-trigger active" href="#[ID_TARGET]">...&lt;/a>
    &lt;a class="tabby-trigger" href="#[ID_TARGET]">...&lt;/a>
&lt;/div>
&lt;div class="tabby-tabs">
    &lt;div class="tabby-tab active" id="[ID]">
        &lt;div class="box">
            ...
        &lt;/div>
    &lt;/div>
    &lt;div class="tabby-tab" id="[ID]">
        &lt;div class="box">
            ...
        &lt;/div>
    &lt;/div>
&lt;/div>
&lt;/div></pre>
                
                <h2>Plain Tab</h2>
                <div class="tabby tabby--plain">
                    <div class="tabby-triggers">
                        <a class="tabby-trigger active" href="#tab11">Tab 1</a>
                        <a class="tabby-trigger" href="#tab22">Tab 2</a>
                    </div>
                    <div class="tabby-tabs">
                        <div class="tabby-tab active" id="tab11">
                                <p>Content tab 1</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorum provident sint, quos nobis voluptatibus doloremque, omnis, obcaecati harum soluta aperiam accusantium. Laboriosam aliquid, aut minima cumque reiciendis, deserunt nostrum vero!</p>
                        </div>
                        <div class="tabby-tab" id="tab22">
                                Content tab 2
                        </div>
                    </div>
                </div>

<pre>
&lt;div class="tabby tabby--plain">
&lt;div class="tabby-triggers">
    &lt;a class="tabby-trigger active" href="#[ID_TARGET]">...&lt;/a>
    &lt;a class="tabby-trigger" href="#[ID_TARGET]">...&lt;/a>
&lt;/div>
&lt;div class="tabby-tabs">
    &lt;div class="tabby-tab active" id="[ID]">
        ...
    &lt;/div>
    &lt;div class="tabby-tab" id="[ID]">
        ...
    &lt;/div>
&lt;/div>
&lt;/div></pre>
            </section>
            <br><br>

            <section class="block">
                <h1>Modal</h1>

                <div class="block">
                    <a class="btn btn--blue" data-modal="MY TITLE">Open modal</a>
                    <div class="modal-content-dummy sr-only">
                        Modal content
                    </div>
                </div>

<pre>
&lt;!-- Create the trigger and the content. -->
&lt;!-- Trigger and content MUST be placed side by side -->
&lt;button class="btn btn--blue" data-modal="MY TITLE">Open modal&lt;/button>
&lt;div class="modal-content-dummy sr-only">
Modal content
&lt;/div>

&lt;!-- Place markup modal at the bottom -->
&lt;!-- Only need one every page -->
&lt;div class="modal">
&lt;div class="modal-dialog">
    &lt;button class="modal-dialog-close-btn">
        &lt;span class="fa fa-fw fa-times">&lt;/span>
    &lt;/button>
    &lt;header class="modal-dialog-header">
        &lt;h3 class="modal-title">&lt;/h3>
    &lt;/header>
    &lt;div class="modal-dialog-content">&lt;/div>
&lt;/div>
&lt;/div>
</pre>
            </section>
            <br><br>

            <section class="block">
                <h2>Dashboard Tab</h2>

                <div class="dashboard-tab dashboard-tab--blue block text-white">
                    <div class="dashboard-tab__navs">
                        <a class="dashboard-tab-nav text-uppercase is-active" href="#dtab2-1">1</a>
                        <a class="dashboard-tab-nav text-uppercase" href="#dtab2-2">2</a>
                        <a class="dashboard-tab-nav text-uppercase" href="#dtab2-3">3</a>
                        <a class="dashboard-tab-nav text-uppercase" href="#dtab2-4">4</a>
                    </div>
                    <div class="dashboard-tab__content is-active" id="dtab2-1">
                        content 1
                    </div>
                    <div class="dashboard-tab__content" id="dtab2-2">
                        content 2
                    </div>
                    <div class="dashboard-tab__content" id="dtab2-3">
                        content 3
                    </div>
                    <div class="dashboard-tab__content" id="dtab2-4">
                        content 4
                    </div>
                </div>

<pre>
&lt;div class="dashboard-tab dashboard-tab--blue block text-white">
&lt;div class="dashboard-tab__navs">
    &lt;a class="dashboard-tab-nav text-uppercase is-active" href="#dtab2-1">1&lt;/a>
    &lt;a class="dashboard-tab-nav text-uppercase" href="#dtab2-2">2&lt;/a>
    &lt;a class="dashboard-tab-nav text-uppercase" href="#dtab2-3">3&lt;/a>
    &lt;a class="dashboard-tab-nav text-uppercase" href="#dtab2-4">4&lt;/a>
&lt;/div>
&lt;div class="dashboard-tab__content is-active" id="dtab2-1">
    content 1
&lt;/div>
&lt;div class="dashboard-tab__content" id="dtab2-2">
    content 2
&lt;/div>
&lt;div class="dashboard-tab__content" id="dtab2-3">
    content 3
&lt;/div>
&lt;div class="dashboard-tab__content" id="dtab2-4">
    content 4
&lt;/div>
&lt;/div>
</pre>
            </section>

            <section>
                <h2>API</h2>

                <dl class="deflist-grid">
                    <dt><code>Suitcore.isImage( filename )</code></dt>
                    <dd>
                        <strong>filename</strong> - String <br>
                        Check whether given filename is an image, return a boolean. Valid image ext:
                        <ul>
                            <li>jpg/jpeg</li>
                            <li>png</li>
                            <li>gif</li>
                            <li>bmp</li>
                        </ul>
                    </dd>
                    
                    <dt><code>Suitcore.isPDF( filename )</code></dt>
                    <dd>
                        <strong>filename</strong> - String <br>
                        Check whether given filename is .pdf, return a boolean.
                    </dd>
                    
                    <dt><code>Suitcore.isDOC( filename )</code></dt>
                    <dd>
                        <strong>filename</strong> - String <br>
                        Check whether given filename is .doc/.docx, return a boolean.
                    </dd>
                    
                    <dt><code>Suitcore.isXLS( filename )</code></dt>
                    <dd>
                        <strong>filename</strong> - String <br>
                        Check whether given filename is .xls/.xlsx, return a boolean.
                    </dd>
                    
                    <dt><code>Suitcore.isPPT( filename )</code></dt>
                    <dd>
                        <strong>filename</strong> - String <br>
                        Check whether given filename is .ppt/.pptx, return a boolean.
                    </dd>

                    <dt><code>Suitcore.supportFileAPI()</code></dt>
                    <dd>Return a boolean. Check whether current browser support <a href="https://developer.mozilla.org/en/docs/Web/API/File">File API</a>.</dd>

                    <dt><code>Suitcore.getUID()</code></dt>
                    <dd>Generate and return a unique ID.</dd>

                    <dt><code>Suitcore.changeTableData( json_url, table )</code></dt>
                    <dd>
                        <strong>json_url</strong> - JSON url path <br>
                        <strong>table</strong> - table DOM element <br>
                        Change content of a datatables
                    </dd>
                </dl>

                <h3>Const</h3>

                <dl class="deflist-grid">
                    <dt><code>Suitcore.chartOpts[ type ]</code></dt>
                    <dd>
                        <strong>type</strong> - String <br>
                        Available type: <br>
                        <ul>
                            <li>bar</li>
                            <li>pie</li>
                            <li>line</li>
                            <li>barLine</li>
                        </ul>
                        Return a JSON config file for each associate chart.
                    </dd>
                </dl>
            </section>
        </div>
    </main>
@stop
