<?php
/**
 * @link      https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license   https://craftcms.com/license
 */

namespace craft\models;

use Craft;
use craft\base\Model;
use craft\helpers\ArrayHelper;
use craft\records\Section as SectionRecord;
use craft\validators\HandleValidator;
use craft\validators\UniqueValidator;

/**
 * Section model class.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since  3.0
 *
 * @property Section_SiteSettings[] $siteSettings Site-specific settings
 */
class Section extends Model
{
    // Constants
    // =========================================================================

    const TYPE_SINGLE = 'single';
    const TYPE_CHANNEL = 'channel';
    const TYPE_STRUCTURE = 'structure';

    // Properties
    // =========================================================================

    /**
     * @var integer ID
     */
    public $id;

    /**
     * @var integer Structure ID
     */
    public $structureId;

    /**
     * @var string Name
     */
    public $name;

    /**
     * @var string Handle
     */
    public $handle;

    /**
     * @var string Type
     */
    public $type;

    /**
     * @var integer Max levels
     */
    public $maxLevels;

    /**
     * @var boolean Enable versioning
     */
    public $enableVersioning = true;

    /**
     * @var
     */
    private $_siteSettings;

    /**
     * @var
     */
    private $_entryTypes;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'structureId', 'maxLevels'], 'number', 'integerOnly' => true],
            [['handle'], HandleValidator::class, 'reservedWords' => ['id', 'dateCreated', 'dateUpdated', 'uid', 'title']],
            [['type'], 'in', 'range' => ['single', 'channel', 'structure']],
            [['name', 'handle'], UniqueValidator::class, 'targetClass' => SectionRecord::class],
            [['name', 'handle', 'type', 'siteSettings'], 'required'],
            [['name', 'handle'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        $validates = parent::validate($attributeNames, $clearErrors);

        if ($attributeNames === null || in_array('siteSettings', $attributeNames)) {
            foreach ($this->getSiteSettings() as $siteSettings) {
                if (!$siteSettings->validate(null, $clearErrors)) {
                    $validates = false;
                }
            }
        }

        return $validates;
    }

    /**
     * Use the translated section name as the string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return Craft::t('site', $this->name);
    }

    /**
     * Returns the section's site-specific settings.
     *
     * @return Section_SiteSettings[]
     */
    public function getSiteSettings()
    {
        if ($this->_siteSettings !== null) {
            return $this->_siteSettings;
        }

        if (!$this->id) {
            return [];
        }

        // Set them with setSiteSettings() so setSection() gets called on them
        $this->setSiteSettings(Craft::$app->getSections()->getSectionSiteSettings($this->id, 'siteId'));

        return $this->_siteSettings;
    }

    /**
     * Sets the section's site-specific settings.
     *
     * @param Section_SiteSettings[] $siteSettings
     *
     * @return void
     */
    public function setSiteSettings($siteSettings)
    {
        $this->_siteSettings = $siteSettings;

        foreach ($this->_siteSettings as $settings) {
            $settings->setSection($this);
        }
    }

    /**
     * Adds site-specific errors to the model.
     *
     * @param array   $errors
     * @param integer $siteId
     *
     * @return void
     */
    public function addSiteSettingsErrors($errors, $siteId)
    {
        foreach ($errors as $attribute => $siteErrors) {
            $key = $attribute.'-'.$siteId;
            foreach ($siteErrors as $error) {
                $this->addError($key, $error);
            }
        }
    }

    /**
     * Returns the section's entry types.
     *
     * @param string|null $indexBy
     *
     * @return EntryType[]
     */
    public function getEntryTypes($indexBy = null)
    {
        if ($this->_entryTypes !== null) {
            return $indexBy ? ArrayHelper::index($this->_entryTypes, $indexBy) : $this->_entryTypes;
        }

        if (!$this->id) {
            return [];
        }

        $this->_entryTypes = Craft::$app->getSections()->getEntryTypesBySectionId($this->id);

        return $indexBy ? ArrayHelper::index($this->_entryTypes, $indexBy) : $this->_entryTypes;
    }
}
