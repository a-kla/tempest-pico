<!-- Dose it work in tempest v3? -->
<ul :as="$this->order ? 'ol' : null" class="list">
    <x-template :foreach="$this->items as $id => $item">
        <li>Item #{{$id}} {{$item}}</li>
    </x-template>
</ul>