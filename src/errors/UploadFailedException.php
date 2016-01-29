<?php
/**
 * @link      http://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license   http://craftcms.com/license
 */

namespace craft\app\errors;

use Craft;

/**
 * Class UploadFailedException
 *
 * @author    Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license   http://craftcms.com/license Craft License Agreement
 * @see       http://craftcms.com
 * @package   craft.app.exceptions
 * @since     3.0
 */
class UploadFailedException extends FileException
{
    public function __construct($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE: {
                $message = Craft::t('app', 'The uploaded file exceeds the maximum allowed size.');
                break;
            }
            case UPLOAD_ERR_PARTIAL:
            case UPLOAD_ERR_NO_FILE: {
                $message = Craft::t('app', 'The file failed to upload to the server properly.');
                break;
            }
            case UPLOAD_ERR_NO_TMP_DIR: {
                $message = Craft::t('app', 'Could not write to the temporary upload folder.');
                break;
            }
            case UPLOAD_ERR_CANT_WRITE: {
                $message = Craft::t('app', 'There was a problem with writing the file to the disk.');
                break;
            }
            default: {
                $message = Craft::t('app', 'There was a problem with uploading the file..');
            }
        }

        parent::__construct($message);
    }
}
