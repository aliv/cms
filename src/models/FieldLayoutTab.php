<?php
/**
 * @link      https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license   https://craftcms.com/license
 */

namespace craft\models;

use Craft;
use craft\base\Field;
use craft\base\FieldInterface;
use craft\base\Model;
use yii\base\InvalidConfigException;

/**
 * FieldLayoutTab model class.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since  3.0
 */
class FieldLayoutTab extends Model
{
    // Properties
    // =========================================================================

    /**
     * @var integer ID
     */
    public $id;

    /**
     * @var integer Layout ID
     */
    public $layoutId;

    /**
     * @var string Name
     */
    public $name;

    /**
     * @var string Sort order
     */
    public $sortOrder;

    /**
     * @var FieldLayout
     */
    private $_layout;

    /**
     * @var FieldInterface[]
     */
    private $_fields;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'layoutId'], 'number', 'integerOnly' => true],
            [['name'], 'string', 'max' => 255],
            [['sortOrder'], 'string', 'max' => 4],
        ];
    }

    /**
     * Returns the tab’s layout.
     *
     * @return FieldLayout|null The tab’s layout.
     * @throws InvalidConfigException if [[groupId]] is set but invalid
     */
    public function getLayout()
    {
        if ($this->_layout !== null) {
            return $this->_layout;
        }

        if (!$this->layoutId) {
            return null;
        }

        if (($this->_layout = Craft::$app->getFields()->getLayoutById($this->layoutId)) === null) {
            throw new InvalidConfigException('Invalid layout ID: '.$this->layoutId);
        }

        return $this->_layout;
    }

    /**
     * Sets the tab’s layout.
     *
     * @param FieldLayout $layout The tab’s layout.
     *
     * @return void
     */
    public function setLayout(FieldLayout $layout)
    {
        $this->_layout = $layout;
    }

    /**
     * Returns the tab’s fields.
     *
     * @return FieldInterface[] The tab’s fields.
     */
    public function getFields()
    {
        if ($this->_fields !== null) {
            return $this->_fields;
        }

        $this->_fields = [];

        if ($layout = $this->getLayout()) {
            foreach ($layout->getFields() as $field) {
                /** @var Field $field */
                /** @noinspection TypeUnsafeComparisonInspection */
                if ($field->tabId == $this->id) {
                    $this->_fields[] = $field;
                }
            }
        }

        return $this->_fields;
    }

    /**
     * Sets the tab’s fields.
     *
     * @param FieldInterface[] $fields The tab’s fields.
     *
     * @return void
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;
    }
}
