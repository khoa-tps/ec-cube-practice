<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Page\Admin;

class PluginManagePage extends AbstractAdminPageStyleGuide
{
    public const 完了メッセージ = '#page_admin_store_plugin > div.c-container > div.c-contentsArea > div.alert:not(.alert-primary).alert-dismissible.fade.show.m-3 > span';

    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function at($I)
    {
        $page = new self($I);

        return $page->atPage('インストールプラグイン一覧オーナーズストア');
    }

    /**
     * @param $pluginCode
     * @param string $message
     *
     * @return PluginManagePage
     */
    public function ストアプラグイン_有効化($pluginCode, $message = '有効にしました。')
    {
        $this->ページ遷移準備();
        $this->ストアプラグイン_ボタンクリック($pluginCode, '有効化');
        $this->ページ遷移を待機();
        $this->tester->waitForText($message, 30, self::完了メッセージ);

        return $this;
    }

    /**
     * @param $pluginCode
     * @param string $message
     *
     * @return PluginManagePage
     */
    public function ストアプラグイン_無効化($pluginCode, $message = '無効にしました。')
    {
        $this->ページ遷移準備();
        $this->ストアプラグイン_ボタンクリック($pluginCode, '無効化');
        $this->ページ遷移を待機();
        $this->tester->waitForText($message, 30, self::完了メッセージ);

        return $this;
    }

    /**
     * @param $pluginCode
     * @param string $message
     *
     * @return PluginManagePage
     *
     * @throws \Exception
     */
    public function ストアプラグイン_削除($pluginCode, $message = '削除が完了しました。')
    {
        $this->ストアプラグイン_ボタンクリック($pluginCode, '削除');
        $this->tester->waitForElementVisible(['id' => 'officialPluginDeleteButton'], 60);
        $this->tester->click(['id' => 'officialPluginDeleteButton']);
        $this->tester->waitForText($message, 30, ['css' => '#officialPluginDeleteModal > div > div > div.modal-body.text-start > p']);
        $this->tester->click(['css' => '#officialPluginDeleteModal > div > div > div.modal-footer > button:nth-child(3)']);

        return $this;
    }

    /**
     * @param $pluginCode
     *
     * @return PluginStoreUpgradePage
     */
    public function ストアプラグイン_アップデート($pluginCode)
    {
        echo $this->tester->grabTextFrom(['xpath' => '//*[@id="page_admin_store_plugin"]']);
        $this->tester->click(['xpath' => $this->ストアプラグイン_セレクタ($pluginCode).'/../../td[5]/a']);

        return PluginStoreUpgradePage::at($this->tester);
    }

    private function ストアプラグイン_ボタンクリック($pluginCode, $label)
    {
        $xpath = ['xpath' => $this->ストアプラグイン_セレクタ($pluginCode).'/../../td[6]//i[@data-bs-original-title="'.$label.'"]/parent::node()'];
        $this->tester->click($xpath);

        return $this;
    }

    public function ストアプラグイン_セレクタ($pluginCode)
    {
        return '//*[@id="page_admin_store_plugin"]//div/h5[contains(text(), "オーナーズストアのプラグイン")]/../..//table/tbody//td[3]/p[contains(text(), "'.$pluginCode.'")]';
    }

    public function 独自プラグイン_有効化($pluginCode)
    {
        $this->ページ遷移準備();
        $this->独自プラグイン_ボタンクリック($pluginCode, '有効化');
        $this->ページ遷移を待機();
        $this->tester->waitForText('有効にしました。', 30, self::完了メッセージ);

        return $this;
    }

    public function 独自プラグイン_無効化($pluginCode)
    {
        $this->ページ遷移準備();
        $this->独自プラグイン_ボタンクリック($pluginCode, '無効化');
        $this->ページ遷移を待機();
        $this->tester->waitForText('無効にしました。', 30, self::完了メッセージ);

        return $this;
    }

    public function 独自プラグイン_削除($pluginCode)
    {
        $this->独自プラグイン_ボタンクリック($pluginCode, '削除');
        $this->tester->waitForElementVisible(['css' => '#localPluginDeleteModal .modal-footer a']);
        $this->ページ遷移準備();
        $this->tester->click(['css' => '#localPluginDeleteModal .modal-footer a']);
        $this->ページ遷移を待機();

        return $this;
    }

    public function 独自プラグイン_アップデート($pluginCode, $pluginDirName)
    {
        $this->tester->compressPlugin($pluginDirName, codecept_data_dir('plugins'));
        $this->tester->attachFile(['xpath' => $this->独自プラグイン_セレクタ($pluginCode).'/../td[5]//input[@type="file"]'], 'plugins/'.$pluginDirName.'.tgz');
        $this->ページ遷移準備();
        $this->tester->click(['xpath' => $this->独自プラグイン_セレクタ($pluginCode).'/../td[5]//button']);
        $this->ページ遷移を待機();
        $this->tester->waitForText('アップデートしました。', 30, self::完了メッセージ);

        return $this;
    }

    private function 独自プラグイン_ボタンクリック($pluginCode, $label)
    {
        $xpath = ['xpath' => $this->独自プラグイン_セレクタ($pluginCode).'/../td[6]//i[@data-bs-original-title="'.$label.'"]/parent::node()'];
        $this->tester->click($xpath);

        return $this;
    }

    private function 独自プラグイン_セレクタ($pluginCode)
    {
        return '//*[@id="page_admin_store_plugin"]//div/h5[contains(text(), "ユーザー独自プラグイン")]/../..//table/tbody//td[3][contains(text(), "'.$pluginCode.'")]/';
    }

    /**
     * ページ遷移を引き起こす click の前に呼び出す。
     * 現在のページに JavaScript マーカーを設定する。
     */
    private function ページ遷移準備()
    {
        $this->tester->executeJS('window.__eccubeNavMarker = true');
    }

    /**
     * ページ遷移の完了を待機する。
     *
     * ページ遷移準備() で設定した JS マーカーが消えるまでポーリングする。
     * ページ再読み込みで window オブジェクトがリセットされるため、
     * 同じ URL へのリダイレクトでも確実に遷移を検出できる。
     *
     * ポーリング中のページ遷移による JS 実行エラーはキャッチして
     * 「遷移中」と判断する。
     */
    private function ページ遷移を待機()
    {
        $this->tester->executeInSelenium(function ($webDriver) {
            $deadline = microtime(true) + 30;
            while (microtime(true) < $deadline) {
                try {
                    $result = $webDriver->executeScript('return window.__eccubeNavMarker === true');
                    if (!$result) {
                        break; // マーカーが消えた = ページが再読み込みされた
                    }
                } catch (\Exception $e) {
                    break; // JS 実行エラー = ページ遷移中
                }
                usleep(500000); // 500ms
            }
        });
        $this->atPage('インストールプラグイン一覧オーナーズストア');

        return $this;
    }
}
