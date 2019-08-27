<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\web\assets\graphiql;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * GraphiQL asset bundle.
 */
class GraphiQlAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/dist';

        $this->depends = [
            VendorAsset::class,
            CpAsset::class,
        ];

        $this->js = [
            'graphiql' . $this->dotJs(),
        ];

        $this->css = [
            'graphiql.css',
        ];

        parent::init();
    }
}