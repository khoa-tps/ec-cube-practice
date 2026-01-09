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

namespace Eccube\Tests\Web\Admin\Setting\System;

use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;
use Faker\Generator;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class LogControllerTest
 */
class LogControllerTest extends AbstractAdminWebTestCase
{
    /** log Test   */
    protected $logTest;

    /** form Data   */
    protected $formData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formData = [
            '_token' => 'dummy',
            'files' => 'site_'.date('Y-m-d').'.log',
            'line_max' => '50',
            'log_level' => '',
            'keyword' => '',
        ];

        $logDir = static::getContainer()->getParameter('kernel.logs_dir');

        $this->logTest = $logDir.'/'.$this->formData['files'];

        if (!file_exists($this->logTest)) {
            file_put_contents($this->logTest, 'test');
        }
    }

    /**
     * rollback
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        if (isset($this->logTest) && file_exists($this->logTest)) {
            unlink($this->logTest);
        }
    }

    /**
     * routing
     */
    public function testRoutingAdminSettingSystemLog()
    {
        $this->client->request(
            'GET',
            $this->generateUrl('admin_setting_system_log')
        );
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     * change log
     */
    public function testSystemLogSubmit()
    {
        $this->client->request(
            'POST',
            $this->generateUrl('admin_setting_system_log'),
            ['admin_system_log' => $this->formData]
        );
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     * Validate test.
     *
     * @param string|int $value
     * @param string $expected
     * @param string $message
     *
     * @dataProvider dataProvider
     */
    public function testSystemLogValidate($value, $expected, $message)
    {
        $this->createTestFile(1);

        $this->formData['line_max'] = $value;

        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'POST',
            $this->generateUrl('admin_setting_system_log'),
            ['admin_system_log' => $this->formData]
        );
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        list($this->actual) = $crawler->filter('#admin_system_log_line_max')->extract(['style']);
        $this->expected = $expected;
        $this->verify();
        if ($message) {
            $this->assertStringContainsString($message, $crawler->filter('.card-body')->html());
        }
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            // FIXME 以下のメッセージが翻訳されない
            // https://github.com/symfony/validator/blob/4.4/Resources/translations/validators.ja.xlf#L270
            ['', '', '入力されていません。'],
            ['a', '', '有効な数字ではありません。'],
            // [0, '', '1以上でなければなりません。'],
            [0, '', ''],
            [50000, '', ''],
            [1.1, '', ''],
            // [100001, '', '50000以下でなければなりません。'],
        ];
    }

    private function createTestFile($number)
    {
        /** @var Generator $faker */
        $faker = $this->getFaker();

        if (!file_exists($this->logTest)) {
            file_put_contents($this->logTest, $faker->paragraphs($number));
        }
    }

    /**
     * Test log level filtering
     */
    public function testLogLevelFiltering()
    {
        $testLogs = [
            '[2025-01-10 10:00:00] admin.DEBUG [] [] [] [] - Debug message',
            '[2025-01-10 10:01:00] admin.INFO [] [] [] [] - Info message',
            '[2025-01-10 10:02:00] admin.WARNING [] [] [] [] - Warning message',
            '[2025-01-10 10:03:00] admin.ERROR [] [] [] [] - Error message',
            '[2025-01-10 10:04:00] admin.CRITICAL [] [] [] [] - Critical message',
        ];
        file_put_contents($this->logTest, implode("\n", $testLogs));

        $this->formData['log_level'] = 'ERROR';
        $crawler = $this->client->request(
            'POST',
            $this->generateUrl('admin_setting_system_log'),
            ['admin_system_log' => $this->formData]
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $html = $crawler->filter('.log-viewer')->html();

        $this->assertStringContainsString('Error message', $html);
        $this->assertStringContainsString('Critical message', $html);
        $this->assertStringNotContainsString('Debug message', $html);
        $this->assertStringNotContainsString('Warning message', $html);
    }

    /**
     * Test keyword search filtering
     */
    public function testKeywordFiltering()
    {
        $testLogs = [
            '[2025-01-10 10:00:00] admin.ERROR [] [] [] [OrderController.php:100] - Payment failed',
            '[2025-01-10 10:01:00] admin.INFO [] [] [] [ProductController.php:200] - Product updated',
        ];
        file_put_contents($this->logTest, implode("\n", $testLogs));

        $this->formData['keyword'] = 'Payment';
        $crawler = $this->client->request(
            'POST',
            $this->generateUrl('admin_setting_system_log'),
            ['admin_system_log' => $this->formData]
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $html = $crawler->filter('.log-viewer')->html();

        $this->assertStringContainsString('Payment failed', $html);
        $this->assertStringNotContainsString('Product updated', $html);
    }

    /**
     * Test combined filtering (log level + keyword)
     */
    public function testCombinedFiltering()
    {
        $testLogs = [
            '[2025-01-10 10:00:00] admin.ERROR [] [] [] [] - Payment validation error',
            '[2025-01-10 10:01:00] admin.WARNING [] [] [] [] - Payment warning',
            '[2025-01-10 10:02:00] admin.ERROR [] [] [] [] - Database connection error',
        ];
        file_put_contents($this->logTest, implode("\n", $testLogs));

        $this->formData['log_level'] = 'ERROR';
        $this->formData['keyword'] = 'Payment';

        $crawler = $this->client->request(
            'POST',
            $this->generateUrl('admin_setting_system_log'),
            ['admin_system_log' => $this->formData]
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $html = $crawler->filter('.log-viewer')->html();

        $this->assertStringContainsString('Payment validation error', $html);
        $this->assertStringNotContainsString('Payment warning', $html);
        $this->assertStringNotContainsString('Database connection error', $html);
    }
}
