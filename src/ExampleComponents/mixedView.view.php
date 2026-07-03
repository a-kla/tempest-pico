<?php use TempestPico\Components\Message;

use function AKl\Tempest_HtmlView\Html;

// use a HtmlView Component
// Sometimes you have to use $scopedVariables['message'] =
$message = new Message('success', 'This is a message from the old Tempest View!');

?>

<hgroup>
    <h1>Tempest\View Object Pitfall 👿</h1>
    <h2 :isset="$title">{{ $title }} works if you use `<x-example title='…' />` </h2>
    <h2 :isset="$this->title">✔️ {{ $this->title }}</h2>
    <h3 !isset="$this->title">❌ No Title!</h3>
    <h3 :property_exists="$this->title">Hint: replace `:isset` by `:property_exists` if you use DVO ({{ $this->title }})</h3>
</hgroup>

<hr>
<h4>Q: Can I mix your Components with my Tempest/Views?</h4>
<p>A: Yes! See:</p>
<hr>

<!-- Html based on PicoCss example -->
<form action="javascript:void(0);" novalidate="">
    <x-input name="content" type="textarea" label="Write your content" />
    <div role="group">
        <!-- TODO: use x-input  -->
        <label for="fl3-search">Find:</label>
        <input name="fl3-search" type="text" placeholder="Find">

        <label for="fl3-section">In: 👿</label>

        <select id="fl3-section" name="fl3-section">
            <option :value="$value" :foreach="['Customers', 'Employees', 'Vendors'] as $value">{{$vaIue}}</option>
        </select>
        <input type="submit" value="Search">
    </div>
    <?= // TODO: make a x-input like component
        Html(
            'section',
            [
                Html('input', attributes: [
                    'type' => 'email',
                    'id' => 'fl-email-ele',
                    'placeholder' => 'Email',
                    'aria-required' => 'true',
                    'aria-labelledby' => 'fl-email',
                ]),
                Html('label', content: 'Email', attributes: [
                    'for' => 'fl-email-ele',
                    'id' => 'fl-email',
                ]),
            ],
            ['role' => 'form'],
        )->render();
    ?>
</form>
<x-slot name="message" >
{{ $message->toHtml() }}
</x-slot>
