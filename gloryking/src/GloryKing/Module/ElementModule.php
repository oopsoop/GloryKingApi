<?php
namespace GloryKing\Module;

use GloryKing\Base\ElementBase;
use Library\ErrorMessage\ErrorMessage;
use Library\FormValidator\Admin\AddElement;
use Library\FormValidator\Admin\EditElement;
use Library\FormValidator\FormValidator;

/**
 * 素材模块
 *
 * Class ElementModule
 * @package GloryKing\Module
 * @author jiangxianli
 * @created_at 2017-04-20 16:55:39
 */
class ElementModule extends Module
{
    /**
     * 获取素材
     *
     * @param array $condition
     * @return array|mixed
     * @author jiangxianli
     * @created_at 2017-04-20 17:14:59
     */
    public static function getElements($condition = [])
    {
        $by = array_get($condition, 'by', '');
        switch ($by) {
            case 'hot':
                //根据热度获取素材
                return ElementBase::getHotElement($condition);
                break;
            case 'hero':
                //根据英雄获取素材
                return ElementBase::getElementByHero($condition);
                break;
            case 'type':
                //根据英雄获取素材
                return ElementBase::getElementByTypeId($condition);
                break;
            case 'all':
                return ElementBase::getAllElement($condition);
                break;
            case 'detail':
                return ElementBase::getElementDetail($condition);
                break;
            case 'recommend':
                return ElementBase::getRecommendElement($condition);
                return;
            default:
                return new ErrorMessage('2003');
                break;
        }
    }

    /**
     * 素材操作
     *
     * @param $condition
     * @param string $operate
     * @return \Library\ErrorMessage\ErrorMessage|mixed
     * @author jiangxianli
     * @created_at 2017-04-24 18:33:10
     */
    public static function elementOperate($condition, $operate = '')
    {
        switch ($operate) {
            case 'add':
                $form_validator = new FormValidator(new AddElement(), $condition);
                if ($form_validator->isFailed()) {
                    return $form_validator->getError();
                }

                return self::dbTransaction(function () use ($condition) {
                    ElementBase::addElement($condition);
                });
                break;
            case 'edit':
                $form_validator = new FormValidator(new EditElement(), $condition);
                if ($form_validator->isFailed()) {
                    return $form_validator->getError();
                }

                return self::dbTransaction(function () use ($condition) {
                    ElementBase::editElement($condition);
                });
                break;
            case 'add_play_num':
                $unique_id = array_get($condition, 'unique_id', 0);
                return ElementBase::incrementByColumn($unique_id, 'play_num');
                break;
            case 'add_raise_num':
                $unique_id = array_get($condition, 'unique_id', 0);
                return ElementBase::incrementByColumn($unique_id, 'raise_num');
                break;
            case 'set_duration':
                $id       = array_get($condition, 'id', 0);
                $duration = array_get($condition, 'duration', 0);
                return ElementBase::setDuration($id, $duration);
                break;
        }
    }
}
