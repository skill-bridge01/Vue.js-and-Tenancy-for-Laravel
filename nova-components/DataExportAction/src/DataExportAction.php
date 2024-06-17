<?php

namespace TestTool\DataExportAction;

use Laravel\Nova\ResourceTool;
use Laravel\Nova\Actions\Action;
class DataExportAction extends Action
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
        public $component = 'data-export-action';
    public function name()
    {
        return 'Dataexportaction';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'data-export-action';
    }
}
