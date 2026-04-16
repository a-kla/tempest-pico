<?php use TempestPico\Components\Message; ?>
<h3>Q: Can I mix your Components with my Tempest/Views?</h3>
<p>A: Yes!:</p>
<form action="javascript:void(0);" novalidate="">
    <div role="group">
        <label for="fl3-search">Find:</label>
        <input name="fl3-search" type="text" placeholder="Find">

        <label for="fl3-section">In:</label>

        <select id="fl3-section" name="fl3-section">
            <!-- Fix me 👿 -->
            <option :value="$value" :foreach="['Customers', 'Employees', 'Vendors'] as $value">{{$vaIue}}</option>
        </select>
        <input type="submit" value="Search">
    </div>
    <?= new Message('info', 'This is a Message… ')->toHtml() ?>
    <section role="form">
        <input type="email" id="fl-email-ele" placeholder="Email" aria-required="true"
            aria-labelledby="fl-email">
        <label for="fl-email-ele" id="fl-email">Email</label>
    </section>
</form>
