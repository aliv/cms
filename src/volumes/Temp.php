<?php
namespace craft\volumes;

use Craft;
use craft\helpers\Url;

/**
 * The temporary volume class. Handles the implementation of a temporary volume
 * Craft.
 *
 * @author     Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @copyright  Copyright (c) 2014, Pixel & Tonic, Inc.
 * @license    http://craftcms.com/license Craft License Agreement
 * @see        http://craftcms.com
 * @package    craft.app.volumes
 * @since      3.0
 */
class Temp extends Local
{
    // Static
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['path'], 'required'];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public static function displayName()
    {
        return Craft::t('app', 'Local Folder');
    }

    /**
     * @inheritdoc
     */
    public static function isLocal()
    {
        return true;
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->path !== null) {
            $this->path = rtrim($this->path, '/');
        } else {
            $this->path = Craft::$app->getPath()->getAssetsTempVolumePath();
        }

        if ($this->url === null) {
            $this->url = rtrim(Url::getResourceUrl(), '/').'/tempassets/';
        }

        if ($this->name === null) {
            $this->name = Craft::t('app', 'Temporary source');
        }
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getRootPath()
    {
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    public function getRootUrl()
    {
        return $this->url;
    }
}
