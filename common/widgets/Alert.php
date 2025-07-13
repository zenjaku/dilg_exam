<?php

namespace common\widgets;

use Yii;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Alert extends \yii\bootstrap5\Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - key: the name of the session flash variable
     * - value: the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error' => 'alert-danger',
        'danger' => 'alert-danger',
        'success' => 'alert-success',
        'info' => 'alert-info',
        'warning' => 'alert-warning'
    ];
    /**
     * @var array the options for rendering the close button tag.
     * Array will be passed to [[\yii\bootstrap\Alert::closeButton]].
     */
    public $closeButton = [];


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $messages) {
            foreach ((array) $messages as $i => $message) {
                $bgClass = $type === 'success' ? 'success' : 'danger';

                $toastBody = \yii\helpers\Html::tag(
                    'div',
                    $message .
                    \yii\helpers\Html::button('', [
                        'type' => 'button',
                        'class' => 'btn-close',
                        'data-bs-dismiss' => 'toast'
                    ]),
                    ['class' => 'toast-body justify-content-between d-flex text-white']
                );

                $toast = \yii\helpers\Html::tag(
                    'div',
                    $toastBody,
                    [
                        'class' => "toast show bg-{$bgClass}",
                        'role' => 'alert',
                        'id' => 'alertMessage'
                    ]
                );

                $container = \yii\helpers\Html::tag(
                    'div',
                    $toast,
                    ['class' => 'toast-container position-fixed top-0 end-0 p-3']
                );

                echo $container;
                // Add the auto-dismiss script
                $this->view->registerJs("setTimeout(() => document.getElementById('alertMessage')?.remove(), 3000);");
            }
            $session->removeFlash($type);
        }
    }
}
