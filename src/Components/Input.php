<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use Tempest\View\GenericView;

use function AKl\Tempest_HtmlView\content;

#[Doc('TBD… temporary x-Input is used… *placeholder* do not work', ['TODO'])]
final class Input extends HtmlView
{
    // @mago-expect lint:excessive-parameter-list
    public function __construct(
        protected(set) string $name,
        protected(set) string $value = '',
        protected(set) ?string $label = null,
        protected(set) ?string $id = null,
        protected(set) ?string $type = 'text',
        protected(set) ?string $default = null,
        protected(set) ?string $placeholder = null, // TODO
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        $adhocView = new GenericView(
            <<<'View'
                    <x-input
                     :name="$name"
                     :label="$label"
                     :id="$id"
                     :type="$type"
                     :default="$default"
                      >{{ $value }}</x-input>
                View,
            data: [
                'name' => $this->name,
                'label' => $this->label,
                'value' => $this->value,
                'id' => $this->id,
                'type' => $this->type,
                'default' => $this->default,
            ],
        );

        return content(
            $adhocView,
            /*
             * Html(
             * 'label',
             * content: 'Find:',
             * attributes: ['for' => 'fl3-search'],
             * ),
             * Html(
             * 'input',
             * attributes: [
             * 'name' => 'fl3-search',
             * 'type' => 'text',
             * 'placeholder' => 'Find',
             * ],
             * ),
             *
             */
        );
    }
}
